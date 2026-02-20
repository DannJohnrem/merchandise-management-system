<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryReorderSetting;

class InventoryReportRow extends Model
{
    protected $table = 'inventory_rows';
    public $timestamps = false;
    protected $guarded = [];

    public static function queryUnion()
    {
        // 1) normalize fixed_assets
        $fixed = DB::table('fixed_assets')
            ->whereNull('fixed_assets.deleted_at')
            ->select([
                DB::raw("'fixed_asset' as source"),
                'fixed_assets.category as category',
                'fixed_assets.asset_name as name',
                'fixed_assets.brand as brand',
                'fixed_assets.model as model',
                'fixed_assets.purchase_cost as unit_price',
                'fixed_assets.status as status',

                DB::raw("
                    LOWER(CONCAT(
                        TRIM(COALESCE(fixed_assets.category,'')), '|',
                        TRIM(COALESCE(fixed_assets.asset_name,'')), '|',
                        TRIM(COALESCE(fixed_assets.brand,'')), '|',
                        TRIM(COALESCE(fixed_assets.model,''))
                    )) as item_key
                "),
            ]);

        // 2) normalize it_leasings
        $lease = DB::table('it_leasings')
            ->whereNull('it_leasings.deleted_at')
            ->select([
                DB::raw("'it_leasing' as source"),
                'it_leasings.category as category',
                'it_leasings.item_name as name',
                'it_leasings.brand as brand',
                'it_leasings.model as model',
                'it_leasings.purchase_cost as unit_price',
                'it_leasings.status as status',

                DB::raw("
                    LOWER(CONCAT(
                        TRIM(COALESCE(it_leasings.category,'')), '|',
                        TRIM(COALESCE(it_leasings.item_name,'')), '|',
                        TRIM(COALESCE(it_leasings.brand,'')), '|',
                        TRIM(COALESCE(it_leasings.model,''))
                    )) as item_key
                "),
            ]);

        $union = $fixed->unionAll($lease);

        // 3) grouped items query (Query\Builder)
        $items = DB::query()
            ->fromSub($union, 'u')
            ->groupBy('u.source', 'u.item_key', 'u.category', 'u.name', 'u.brand', 'u.model')
            ->select([
                DB::raw("CONCAT(u.source, '-', u.item_key) as row_id"),
                'u.source',
                'u.item_key',
                'u.category',
                'u.name',
                'u.brand',
                'u.model',
                DB::raw("ROUND(AVG(COALESCE(u.unit_price,0)), 2) as unit_price"),
                DB::raw("
                    SUM(
                        CASE WHEN u.status = 'available' THEN 1 ELSE 0 END
                    ) as quantity_in_stock
                "),
                DB::raw("COUNT(*) as total_units"),
            ]);

        // 4) join reorder settings (Query\Builder)
        $rsTable = (new InventoryReorderSetting())->getTable();

        $final = DB::query()
            ->fromSub($items, 'items')
            ->leftJoin($rsTable . ' as rs', function ($join) {
                $join->on('items.source', '=', 'rs.source')
                     ->on('items.item_key', '=', 'rs.item_key');
            })
            ->select([
                'items.*',
                DB::raw("COALESCE(rs.reorder_level, 0) as reorder_level"),
                DB::raw("COALESCE(rs.reorder_time_days, NULL) as reorder_time_days"),
                DB::raw("COALESCE(rs.discontinued, 0) as discontinued"),
                DB::raw("(items.unit_price * items.quantity_in_stock) as inventory_value"),
                DB::raw("
                    CASE
                        WHEN COALESCE(rs.discontinued,0) = 1 THEN 0
                        WHEN items.quantity_in_stock <= COALESCE(rs.reorder_level,0) THEN 1
                        ELSE 0
                    END as for_reorder
                "),
            ]);

        // ✅ IMPORTANT: return Eloquent\Builder for Rappasoft
        return static::query()->fromSub($final, 'inventory_rows');
    }
}
