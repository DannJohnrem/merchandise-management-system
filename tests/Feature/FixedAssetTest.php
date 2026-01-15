<?php

use App\Models\FixedAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a fixed asset using factory', function () {
    $fixedAsset = FixedAsset::factory()->create();

    expect($fixedAsset)->toBeInstanceOf(FixedAsset::class)
        ->and($fixedAsset->asset_tag)->toStartWith('HP-')
        ->and($fixedAsset->category)->toBe('Laptop')
        ->and($fixedAsset->brand)->toBe('HP')
        ->and($fixedAsset->status)->toBe('available');
});


it('can update fixed asset fields', function () {
    $asset = FixedAsset::factory()->create();

    $asset->update([
        'status' => 'available', // or 'in-use' / 'disposed' — must match DB
        'assigned_employee' => 'Juan Dela Cruz',
        'condition' => 'good',
    ]);

    $asset->refresh();

    expect($asset->assigned_employee)->toBe('Juan Dela Cruz')
        ->and($asset->condition)->toBe('good');
});

it('soft deletes a fixed asset', function () {
    $asset = FixedAsset::factory()->create();

    $asset->delete();

    expect(FixedAsset::count())->toBe(0)
        ->and(FixedAsset::withTrashed()->count())->toBe(1);
});
