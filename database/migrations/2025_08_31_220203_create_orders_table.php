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
            $table->id('order_id');
            $table->unsignedBigInteger('farmer_id');   // farmer who ordered
            $table->unsignedBigInteger('fertilizer_id'); // fertilizer ordered
            $table->unsignedBigInteger('agrovet_id');   // agrovet fulfilling the order
            $table->integer('quantity');   // qty ordered
            $table->decimal('total_price', 10, 2); // total = qty × price
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // foreign keys
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreign('fertilizer_id')->references('fertilizer_id')->on('fertilizers')->onDelete('cascade');
            $table->foreign('agrovet_id')->references('id')->on('agrovets')->onDelete('cascade');

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
