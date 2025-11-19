<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update agrovets table with names from users table
        DB::statement('UPDATE agrovets a JOIN users u ON a.user_id = u.id SET a.name = u.name');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear the name column
        DB::table('agrovets')->update(['name' => null]);
    }
};
