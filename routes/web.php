<?php

use Laravel\Fortify\Features;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Roles\RoleEdit;
use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\Class\ClassEdit;
use App\Livewire\Admin\Roles\RoleIndex;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Admin\Class\ClassIndex;
use App\Livewire\Admin\Roles\RoleCreate;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Admin\Class\ClassCreate;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\ItLeasingQrController;
use App\Livewire\Pages\ItLeasing\ItLeasingEdit;
use App\Livewire\Pages\ItLeasing\ItLeasingIndex;
use App\Livewire\Pages\FixedAsset\FixedAssetEdit;
use App\Livewire\Pages\ItLeasing\ItLeasingCreate;
use App\Livewire\Admin\Permissions\PermissionEdit;
use App\Livewire\Pages\FixedAsset\FixedAssetIndex;
use App\Livewire\Admin\Permissions\PermissionIndex;
use App\Livewire\Pages\FixedAsset\FixedAssetCreate;
use App\Livewire\Admin\Permissions\PermissionCreate;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public signed QR route (no auth)
Route::get('/it-leasing/{item}/qr', [ItLeasingQrController::class, 'show'])
    ->name('it-leasing.show');

Route::get('fixed-asset/{item}/qr', [FixedAssetController::class, 'show'])
    ->name('fixed-asset.show');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::prefix('it-leasing')->name('it-leasing.')->group(function () {
        Route::get('/', ItLeasingIndex::class)->name('index');
        Route::get('/create', ItLeasingCreate::class)->name('create');
        Route::get('/{item}/edit', ItLeasingEdit::class)->name('edit');
    });

    Route::prefix('fixed-asset')->name('fixed-asset.')->group(function () {
        Route::get('/', FixedAssetIndex::class)->name('index');
        Route::get('/create', FixedAssetCreate::class)->name('create');
        Route::get('/{asset}/edit', FixedAssetEdit::class)->name('edit');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

        // Roles
        Route::get('/roles', RoleIndex::class)->name('roles.index');
        Route::get('/roles/create', RoleCreate::class)->name('roles.create');
        Route::get('/roles/{role}/edit', RoleEdit::class)->name('roles.edit');

        //vPermissions
        Route::get('/permissions', PermissionIndex::class)->name('permissions.index');
        Route::get('/permissions/create', PermissionCreate::class)->name('permissions.create');
        Route::get('/permissions/{permission}/edit', PermissionEdit::class)->name('permissions.edit');

        // Classes
        Route::get('/class', ClassIndex::class)->name('class.index');
        Route::get('/class/create', ClassCreate::class)->name('class.create');
        Route::get('/class/{class}/edit', ClassEdit::class)->name('class.edit');
    });
});
