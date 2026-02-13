<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryReportRow extends Model
{
    protected $table = 'inventory'; // ✅ IMPORTANT
    public $timestamps = false;
    protected $guarded = [];

    public static function queryUnion()
    {
        $fixedAssets = DB::table('fixed_assets')
            ->whereNull('fixed_assets.deleted_at')
            ->select([
                DB::raw("'fixed_asset' as source"),
                DB::raw("CONCAT('fixed_asset-', fixed_assets.id) as row_id"),
                'fixed_assets.id as source_id',
                'fixed_assets.asset_tag as asset_tag',
                'fixed_assets.category as category',
                'fixed_assets.asset_name as item_name',
                'fixed_assets.serial_number as serial_number',
                'fixed_assets.brand as brand',
                'fixed_assets.model as model',
                'fixed_assets.purchase_cost as purchase_cost',
                'fixed_assets.assigned_employee as assigned_employee',
                'fixed_assets.location as location',
                'fixed_assets.status as status',
                'fixed_assets.condition as item_condition',
                'fixed_assets.purchase_date as purchase_date',
                'fixed_assets.warranty_expiration as warranty_expiration',
            ]);

        $itLeasing = DB::table('it_leasings')
            ->whereNull('it_leasings.deleted_at')
            ->select([
                DB::raw("'it_leasing' as source"),
                DB::raw("CONCAT('it_leasing-', it_leasings.id) as row_id"),
                'it_leasings.id as source_id',
                DB::raw("NULL as asset_tag"),
                'it_leasings.category as category',
                'it_leasings.item_name as item_name',
                'it_leasings.serial_number as serial_number',
                'it_leasings.brand as brand',
                'it_leasings.model as model',
                'it_leasings.purchase_cost as purchase_cost',
                'it_leasings.assigned_employee as assigned_employee',
                'it_leasings.location as location',
                'it_leasings.status as status',
                'it_leasings.condition as item_condition',
                'it_leasings.purchase_date as purchase_date',
                'it_leasings.warranty_expiration as warranty_expiration',
            ]);

        $union = $fixedAssets->unionAll($itLeasing);

        return static::query()->fromSub($union, 'inventory');
    }
}
