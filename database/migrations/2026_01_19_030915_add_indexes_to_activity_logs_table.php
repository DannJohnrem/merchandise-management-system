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
        Schema::table('activity_logs', function (Blueprint $table) {
            // for default sort: created_at desc
            $table->index('created_at');

            // for user column sorting/filtering
            $table->index('user_id');

            // for action sorting/filtering (created/updated/deleted/etc)
            $table->index('action');

            // optional but helpful if you filter by module only
            $table->index('subject_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['subject_type']);
        });
    }
};
