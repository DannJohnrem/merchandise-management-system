<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_tag',
        'category',
        'asset_name',
        'serial_number',
        'charger_serial_number',
        'brand',
        'model',
        'purchase_cost',
        'supplier',
        'assigned_employee',
        'department',
        'asset_class',
        'location',
        'status',
        'condition',
        'warranty_expiration',
        'purchase_date',
        'purchase_order_no',
        'remarks',
        'inclusions',
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'purchase_date' => 'date:Y-m-d',
        'warranty_expiration' => 'date:Y-m-d',
        'inclusions' => 'array',
    ];
}
