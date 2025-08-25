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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
           // $table->unsignedBigInteger('farmer_id');   // FK to farmers
            $table->unsignedBigInteger('agrovet_id');  // FK to agrovets
            $table->unsignedBigInteger('fertilizer_id'); // FK to fertilizers
            $table->integer('quantity');
            $table->date('order_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

          //  $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('agrovet_id')->references('id')->on('agrovets')->onDelete('cascade');
            $table->foreign('fertilizer_id')->references('id')->on('fertilizers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
