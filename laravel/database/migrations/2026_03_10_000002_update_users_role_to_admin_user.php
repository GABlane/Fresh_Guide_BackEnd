<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remap legacy roles to admin before the column change
        DB::table('users')
            ->whereIn('role', ['editor', 'viewer'])
            ->update(['role' => 'admin']);

        // MySQL: alter enum; SQLite uses TEXT so no DDL change needed
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','editor','viewer') NOT NULL DEFAULT 'viewer'");
        }
    }
};
