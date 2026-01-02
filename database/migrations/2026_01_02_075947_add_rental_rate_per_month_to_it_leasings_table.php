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
        Schema::table('it_leasings', function (Blueprint $table) {
        $table->decimal('rental_rate_per_month', 10, 2)->nullable()->after('purchase_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('it_leasings', function (Blueprint $table) {
            $table->dropColumn('rental_rate_per_month');
        });
    }
};
