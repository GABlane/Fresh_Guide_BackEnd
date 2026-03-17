<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('client_uuid');
            $table->string('title', 255);
            $table->string('course_code', 100)->nullable();
            $table->string('instructor', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('color_hex', 20)->nullable();
            $table->unsignedTinyInteger('day_of_week');
            $table->unsignedSmallInteger('start_minutes');
            $table->unsignedSmallInteger('end_minutes');
            $table->boolean('is_online')->default(false);
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('online_platform', 255)->nullable();
            $table->unsignedSmallInteger('reminder_minutes')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'client_uuid']);
            $table->index(['user_id', 'day_of_week', 'start_minutes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_entries');
    }
};
