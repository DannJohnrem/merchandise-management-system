<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pull_out_forms', function (Blueprint $table) {
            $table->json('reasons')->nullable()->after('employee_name');
        });

        Schema::table('pull_out_forms', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pull_out_forms', function (Blueprint $table) {
            $table->dropColumn('reasons');
            $table->enum('reason', ['assessment', 'return', 'overissuance', 'incompatible_specs', 'others'])
                ->default('return')
                ->after('employee_name');
        });
    }
};
