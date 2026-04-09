<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('director')->nullable()->after('name');
            $table->string('genre')->nullable()->after('director');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['director', 'genre']);
        });
    }
};