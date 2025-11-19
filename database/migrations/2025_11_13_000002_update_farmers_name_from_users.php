<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update farmers table with names from users table
        DB::statement('UPDATE farmers f JOIN users u ON f.user_id = u.id SET f.`Farmer name` = u.name');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear the name column
        DB::table('farmers')->update(['name' => null]);
    }
};
