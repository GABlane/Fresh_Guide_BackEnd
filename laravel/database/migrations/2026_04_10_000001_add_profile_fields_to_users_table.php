<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('course_section', 100)->nullable()->after('campus_code');
            $table->string('profile_photo_path', 1024)->nullable()->after('course_section');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['course_section', 'profile_photo_path']);
        });
    }
};
