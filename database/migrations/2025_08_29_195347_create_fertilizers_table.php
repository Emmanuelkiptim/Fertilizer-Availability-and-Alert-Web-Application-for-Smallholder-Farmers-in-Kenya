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
            $table->id('fertilizer_id');             // primary key (custom name)
            $table->unsignedBigInteger('agrovet_id');// links to agrovets.id
            $table->string('name');
            $table->string('type');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->boolean('availability')->default(true);
            $table->timestamps();

            $table->foreign('agrovet_id')
              ->references('id')->on('agrovets')
              ->onDelete('cascade');
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
