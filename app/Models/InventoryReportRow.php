<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryReportRow extends Model
{
    protected $table = 'inventory_rows';
    public $timestamps = false;
    protected $guarded = [];

    protected const NOT_ON_HAND_STATUSES = ['deployed', 'lost'];

    public static function queryUnion()
    {
        $notOnHandList = "'" . implode("','", self::NOT_ON_HAND_STATUSES) . "'";

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

        // 1a) Fixed assets
        $fixedAssets = DB::table('fixed_assets')
            ->whereNull('deleted_at')
            ->selectRaw("
                'fixed_asset' as source,
                category,
                asset_name as name,
                brand,
                model,
                purchase_cost as unit_price,
                purchase_date as purchase_date,
                status,
                {$generateItemKey('fixed_assets', 'asset_name')} as item_key
            ");

        // 1b) IT leasing
        $itLeasing = DB::table('it_leasings')
            ->whereNull('deleted_at')
            ->selectRaw("
                'it_leasing' as source,
                category,
                item_name as name,
                brand,
                model,
                purchase_cost as unit_price,
                purchase_date as purchase_date,
                status,
                {$generateItemKey('it_leasings', 'item_name')} as item_key
            ");

        // 1c) IT leasing inclusions - inherit parent's purchase_date
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
                il.purchase_date as purchase_date,
                il.status,
                LOWER(CONCAT_WS('|', 'inclusion', REGEXP_REPLACE(TRIM(COALESCE(il.category, '')), '\\\\s+', ' '), REGEXP_REPLACE(TRIM(jt.inclusion_name), '\\\\s+', ' '))) as item_key
            ");

        $unionedSources = $fixedAssets
            ->unionAll($itLeasing)
            ->unionAll($itLeasingInclusions);

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
                MIN(u.purchase_date) as purchase_date,
                SUM(CASE WHEN u.status NOT IN ({$notOnHandList}) THEN 1 ELSE 0 END) as quantity_in_stock,
                ROUND(AVG(NULLIF(u.unit_price, 0)), 2) as unit_price,
                ROUND(
                    SUM(CASE WHEN u.status NOT IN ({$notOnHandList}) THEN 1 ELSE 0 END)
                    * AVG(NULLIF(u.unit_price, 0)),
                    2
                ) as inventory_value
            ");

        return static::query()->fromSub($aggregatedItems, 'inventory_rows');
    }
}
