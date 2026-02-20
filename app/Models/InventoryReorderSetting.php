<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryReorderSetting extends Model
{
    protected $table = 'inventory_reorder_settings';

    protected $fillable = [
        'source',
        'item_key',
        'category',
        'name',
        'brand',
        'model',
        'reorder_level',
        'reorder_time_days',
        'discontinued',
    ];

    protected $casts = [
        'reorder_level' => 'integer',
        'reorder_time_days' => 'integer',
        'discontinued' => 'boolean',
    ];

    public const SOURCE_FIXED_ASSET = 'fixed_asset';
    public const SOURCE_IT_LEASING  = 'it_leasing';

    public static function makeItemKey(?string $category, ?string $name, ?string $brand, ?string $model): string
    {
        return strtolower(implode('|', [
            trim((string) $category),
            trim((string) $name),
            trim((string) $brand),
            trim((string) $model),
        ]));
    }
}
