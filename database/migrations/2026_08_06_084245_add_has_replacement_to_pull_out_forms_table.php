<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pull_out_forms', function (Blueprint $table) {
            if (! Schema::hasColumn('pull_out_forms', 'has_replacement')) {
                $table->boolean('has_replacement')->default(false)->after('condition_details');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pull_out_forms', function (Blueprint $table) {
            $table->dropColumn('has_replacement');
        });
    }
};
