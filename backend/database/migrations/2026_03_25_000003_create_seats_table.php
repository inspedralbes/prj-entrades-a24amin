<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_zone_id')->constrained('event_zones')->onDelete('cascade');
            $table->integer('row');
            $table->integer('col');
            $table->enum('status', ['available', 'reserved', 'occupied'])->default('available');
            $table->timestamps();

            $table->unique(['event_zone_id', 'row', 'col']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};