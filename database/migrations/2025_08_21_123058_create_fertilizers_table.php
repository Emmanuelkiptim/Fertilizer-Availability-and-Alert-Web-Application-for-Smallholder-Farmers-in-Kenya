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
        Schema::create('fertilizers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agrovet_id'); // FK to agrovets table
            $table->string('name');       // Fertilizer name
            $table->string('type');       // Fertilizer type (e.g. NPK, UREA)
            $table->integer('quantity');  // Stock quantity
            $table->decimal('price', 8, 2); // Price
            $table->boolean('availability')->default(1); // Availability flag
            $table->timestamps();
           // $table->foreign('agrovet_id')
                 // ->references('id')  // matches agrovets.id
                 // ->on('agrovets')
                  //->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizers');
    }
};
