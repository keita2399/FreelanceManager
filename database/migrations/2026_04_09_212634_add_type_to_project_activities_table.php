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
        Schema::table('project_activities', function (Blueprint $table) {
            $table->string('type')->default('memo')->after('id'); // memo | status_change
            $table->string('old_value')->nullable()->after('content');
            $table->string('new_value')->nullable()->after('old_value');
        });
    }

    public function down(): void
    {
        Schema::table('project_activities', function (Blueprint $table) {
            $table->dropColumn(['type', 'old_value', 'new_value']);
        });
    }
};
