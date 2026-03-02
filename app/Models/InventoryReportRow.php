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
        $generateItemKey = function ($table, $nameColumn) {
            return "LOWER(CONCAT(
                TRIM(COALESCE({$table}.category,'')), '|',
                TRIM(COALESCE({$table}.{$nameColumn},'')), '|',
                TRIM(COALESCE({$table}.brand,'')), '|',
                TRIM(COALESCE({$table}.model,''))
            ))";
        };

        // 1) fixed_assets
        $fixed = DB::table('fixed_assets')
            ->whereNull('fixed_assets.deleted_at')
            ->selectRaw("
                'fixed_asset' as source,
                fixed_assets.category as category,
                fixed_assets.asset_name as name,
                fixed_assets.brand as brand,
                fixed_assets.model as model,
                fixed_assets.purchase_cost as unit_price,
                fixed_assets.status as status,
                {$generateItemKey('fixed_assets', 'asset_name')} as item_key
            ");

        // 2) it_leasings
        $lease = DB::table('it_leasings')
            ->whereNull('it_leasings.deleted_at')
            ->selectRaw("
                'it_leasing' as source,
                it_leasings.category as category,
                it_leasings.item_name as name,
                it_leasings.brand as brand,
                it_leasings.model as model,
                it_leasings.purchase_cost as unit_price,
                it_leasings.status as status,
                {$generateItemKey('it_leasings', 'item_name')} as item_key
            ");

        $union = $fixed->unionAll($lease);

        // 3) group items
        $items = DB::query()
            ->fromSub($union, 'u')
            ->groupBy('u.source', 'u.item_key', 'u.category', 'u.name', 'u.brand', 'u.model')
            ->selectRaw("
                CONCAT(u.source, '-', u.item_key) as row_id,
                u.source,
                u.item_key,
                u.category,
                u.name,
                u.brand,
                u.model,
                ROUND(AVG(COALESCE(u.unit_price,0)), 2) as unit_price,
                SUM(CASE WHEN u.status = 'available' THEN 1 ELSE 0 END) as quantity_in_stock,
                SUM(CASE WHEN u.status = 'available' THEN COALESCE(u.unit_price, 0) ELSE 0 END) as inventory_value,
                COUNT(*) as total_units
            ");

        // 4) join reorder settings
        $final = DB::query()
            ->fromSub($items, 'items')
            ->leftJoin('inventory_reorder_settings as rs', function ($join) {
                $join->on('items.source', '=', 'rs.source')
                     ->on('items.item_key', '=', 'rs.item_key');
            })
            // ✅ IMPORTANT: explicit select includes items.item_key (so it will NEVER be null)
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
                    WHEN COALESCE(rs.discontinued,0) = 1 THEN 0
                    WHEN items.quantity_in_stock < COALESCE(rs.reorder_level,0) THEN 1
                    ELSE 0
                END as for_reorder
            ");

        // ✅ Rappasoft needs Eloquent\Builder
        return static::query()->fromSub($final, 'inventory_rows');
    }
}
