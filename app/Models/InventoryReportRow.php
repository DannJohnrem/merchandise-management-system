<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryReportRow extends Model
{
    protected $table = 'inventory_rows';
    public $timestamps = false;
    protected $guarded = [];

    public static function queryUnion()
    {
        // Helper to generate consistent item_key SQL
        $generateItemKey = function (string $table, string $nameColumn): string {
            // CONCAT_WS is cleaner as it handles the separator.
            return "LOWER(CONCAT_WS('|',
                TRIM(COALESCE({$table}.category, '')),
                TRIM(COALESCE({$table}.{$nameColumn}, '')),
                TRIM(COALESCE({$table}.brand, '')),
                TRIM(COALESCE({$table}.model, ''))
            ))";
        };

        // 1) Union of all inventory sources
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

        $unionedSources = $fixedAssets->unionAll($itLeasing);

        // 2) Group and aggregate items from the unioned sources
        $aggregatedItems = DB::query()
            ->fromSub($unionedSources, 'u')
            ->groupBy('u.source', 'u.item_key', 'u.category', 'u.name', 'u.brand', 'u.model')
            ->selectRaw("
                CONCAT(u.source, '-', u.item_key) as row_id,
                u.source,
                u.item_key,
                u.category,
                u.name,
                u.brand,
                u.model,
                ROUND(AVG(COALESCE(u.unit_price, 0)), 2) as unit_price,
                SUM(CASE WHEN u.status = 'available' THEN 1 ELSE 0 END) as quantity_in_stock,
                SUM(CASE WHEN u.status = 'available' THEN COALESCE(u.unit_price, 0) ELSE 0 END) as inventory_value,
                COUNT(*) as total_units
            ");

        // 3) Final query joining the aggregated items with reorder settings
        $finalQuery = DB::query()
            ->fromSub($aggregatedItems, 'items')
            ->leftJoin('inventory_reorder_settings as rs', function ($join) {
                $join->on('items.source', '=', 'rs.source')
                     ->on('items.item_key', '=', 'rs.item_key');
            })
            ->selectRaw("
                items.row_id,
                items.source,
                items.item_key,
                items.category,
                items.name,
                items.brand,
                items.model,
                items.unit_price,
                items.quantity_in_stock,
                items.inventory_value,
                items.total_units,
                COALESCE(rs.reorder_level, 0) as reorder_level,
                rs.reorder_time_days,
                COALESCE(rs.discontinued, 0) as discontinued,
                CASE
                    WHEN COALESCE(rs.discontinued, 0) = 1 THEN 0
                    WHEN items.quantity_in_stock <= COALESCE(rs.reorder_level, 0) THEN 1
                    ELSE 0
                END as for_reorder
            ");

        // Rappasoft Livewire Tables requires an Eloquent Builder instance
        return static::query()->fromSub($finalQuery, 'inventory_rows');
    }
}
