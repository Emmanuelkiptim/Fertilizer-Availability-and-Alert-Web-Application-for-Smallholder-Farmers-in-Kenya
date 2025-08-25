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
        Schema::create('alert', function (Blueprint $table) {
                $table->id();
               // $table->unsignedBigInteger('farmer_id'); // FK to farmers
                $table->string('message');
                $table->timestamp('created_at')->useCurrent();

               // $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert');
    }
};
