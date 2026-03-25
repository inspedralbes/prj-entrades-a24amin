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
            $table->string('identifier');
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->foreignId('reserved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('reserved_until')->nullable();
            $table->timestamps();

            $table->unique(['event_zone_id', 'identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};