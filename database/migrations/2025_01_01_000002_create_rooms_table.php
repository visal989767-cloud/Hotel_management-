<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // FK table (like "Students") -> belongs to room_types
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->string('room_number')->unique();
            $table->unsignedTinyInteger('floor')->default(1);
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('description')->nullable();
            $table->foreignId('room_type_id')
                ->constrained('room_types', 'room_type_id')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
