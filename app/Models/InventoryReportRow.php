<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryReportRow extends Model
{
    protected $table = 'inventory_rows';
    public $timestamps = false;
    protected $guarded = [];

    // Statuses that mean the unit is NOT physically on hand / not sellable-available.
    // Everything else (available, in_repair, returned, etc.) still counts toward
    // "quantity in stock" per your instruction - only deployed/lost are excluded.
    protected const NOT_ON_HAND_STATUSES = ['deployed', 'lost'];

    public static function queryUnion()
    {
        $notOnHandList = "'" . implode("','", self::NOT_ON_HAND_STATUSES) . "'";

        // Helper to generate consistent item_key SQL for the "main" item rows.
        // Collapses ALL whitespace (leading/trailing/internal double-spaces)
        // down to single spaces so "HP  Laptop" and "HP Laptop" end up equal.
        $normalize = function (string $expr): string {
            return "REGEXP_REPLACE(TRIM(COALESCE({$expr}, '')), '\\\\s+', ' ')";
        };

        $generateItemKey = function (string $table, string $nameColumn) use ($normalize): string {
            $categoryExpr = $normalize("{$table}.category");
            $nameExpr = $normalize("{$table}.{$nameColumn}");
            $brandExpr = $normalize("{$table}.brand");
            $modelExpr = $normalize("{$table}.model");

            return "LOWER(CONCAT_WS('|', {$categoryExpr}, {$nameExpr}, {$brandExpr}, {$modelExpr}))";
        };

        // 1a) Fixed assets (main rows, unchanged)
        $fixedAssets = DB::table('fixed_assets')
            ->whereNull('deleted_at')
            ->selectRaw("
                'fixed_asset' as source,
                category,
                asset_name as name,
                brand,
                model,
                purchase_cost as unit_price,
                status,
                {$generateItemKey('fixed_assets', 'asset_name')} as item_key
            ");

        // 1b) IT leasing (main rows) - one row per category+name+brand+model,
        // no duplicate rows per unit, just aggregated counts.
        $itLeasing = DB::table('it_leasings')
            ->whereNull('deleted_at')
            ->selectRaw("
                'it_leasing' as source,
                category,
                item_name as name,
                brand,
                model,
                purchase_cost as unit_price,
                status,
                {$generateItemKey('it_leasings', 'item_name')} as item_key
            ");

        // 1c) IT leasing INCLUSIONS - unnest the `inclusions` JSON array so each
        // distinct inclusion item (e.g. "Charger", "Laptop Bag") becomes its own
        // row. Its status is inherited from the parent it_leasing record, so if
        // the parent laptop is 'deployed' or 'lost', its charger/bag also drop
        // out of the on-hand count - same logic as the main item rows.
        //
        // Assumes `inclusions` is a simple JSON array of strings:
        //   ["Charger", "Laptop Bag", "Wireless Mouse"]
        // If it's an array of objects like [{"name": "Charger"}], change the
        // JSON_TABLE PATH below from '$' to '$.name'.
        $itLeasingInclusions = DB::table(DB::raw("it_leasings il,
                JSON_TABLE(
                    COALESCE(il.inclusions, '[]'),
                    '$[*]' COLUMNS (inclusion_name VARCHAR(255) PATH '$')
                ) as jt"))
            ->whereNull('il.deleted_at')
            ->whereRaw("JSON_LENGTH(COALESCE(il.inclusions, '[]')) > 0")
            ->whereRaw("TRIM(COALESCE(jt.inclusion_name, '')) != ''")
            ->selectRaw("
                'it_leasing_inclusion' as source,
                CONCAT(il.category, ' - Inclusion') as category,
                jt.inclusion_name as name,
                NULL as brand,
                NULL as model,
                0 as unit_price,
                il.status,
                LOWER(CONCAT_WS('|', 'inclusion', REGEXP_REPLACE(TRIM(COALESCE(il.category, '')), '\\\\s+', ' '), REGEXP_REPLACE(TRIM(jt.inclusion_name), '\\\\s+', ' '))) as item_key
            ");

        // 2) Union everything together
        $unionedSources = $fixedAssets
            ->unionAll($itLeasing)
            ->unionAll($itLeasingInclusions);

        // 3) Group and aggregate. quantity_in_stock now counts every status
        // EXCEPT 'deployed' and 'lost' - so 'available', 'in_repair', 'returned',
        // etc. all still count as physically on hand.
        $aggregatedItems = DB::query()
            ->fromSub($unionedSources, 'u')
            ->groupBy('u.source', 'u.item_key')
            ->selectRaw("
                CONCAT(u.source, '-', u.item_key) as row_id,
                u.source,
                u.item_key,
                MIN(u.category) as category,
                MIN(u.name) as name,
                MIN(u.brand) as brand,
                MIN(u.model) as model,
                ROUND(AVG(COALESCE(u.unit_price, 0)), 2) as unit_price,
                SUM(CASE WHEN u.status NOT IN ({$notOnHandList}) THEN 1 ELSE 0 END) as quantity_in_stock,
                SUM(CASE WHEN u.status NOT IN ({$notOnHandList}) THEN COALESCE(u.unit_price, 0) ELSE 0 END) as inventory_value,
                COUNT(*) as total_units
            ");

        // Rappasoft Livewire Tables requires an Eloquent Builder instance
        return static::query()->fromSub($aggregatedItems, 'inventory_rows');
    }
}
