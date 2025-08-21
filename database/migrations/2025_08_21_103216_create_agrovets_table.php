<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agrovets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('systemusers')
                  ->onDelete('cascade');
            $table->string('shop_name');
            $table->string('location(Latitiude)');
            $table->string('location(Longitude)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrovets');
    }
};
