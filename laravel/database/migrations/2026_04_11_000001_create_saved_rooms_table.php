<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->timestamp('saved_at');
            $table->timestamps();

            $table->unique(['user_id', 'room_id']);
            $table->index(['user_id', 'saved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_rooms');
    }
};
