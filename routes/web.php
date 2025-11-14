<?php

use Laravel\Fortify\Features;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Roles\RoleEdit;
use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\Roles\RoleIndex;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Admin\Roles\RoleCreate;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Pages\ItLeasing\ItLeasingEdit;
use App\Livewire\Pages\ItLeasing\ItLeasingIndex;
use App\Livewire\Pages\ItLeasing\ItLeasingCreate;
use App\Livewire\Admin\Permissions\PermissionEdit;
use App\Livewire\Admin\Permissions\PermissionIndex;
use App\Livewire\Admin\Permissions\PermissionCreate;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

    Route::prefix('pages')->name('pages.')->group(function () {
        // It Leasing
        Route::get('/it-leasing', ItLeasingIndex::class)->name('it-leasing.index');
        Route::get('/it-leasing/create', ItLeasingCreate::class)->name('it-leasing.create');
        Route::get('/it-leasing/{item}/edit', ItLeasingEdit::class)->name('it-leasing.edit');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

        //Roles
        Route::get('/roles', RoleIndex::class)->name('roles.index');
        Route::get('/roles/create', RoleCreate::class)->name('roles.create');
        Route::get('/roles/{role}/edit', RoleEdit::class)->name('roles.edit');

        //Permissions
        Route::get('/permissions', PermissionIndex::class)->name('permissions.index');
        Route::get('/permissions/create', PermissionCreate::class)->name('permissions.create');
        Route::get('/permissions/{permission}/edit', PermissionEdit::class)->name('permissions.edit');
    });
});
