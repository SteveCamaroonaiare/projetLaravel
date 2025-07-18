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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->enum('sexes', ['Hommes', 'Femmes', 'Enfants']);
            $table->string('image'); // Chemin de l'image
            $table->boolean('is_new')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_promo')->default(false);
            $table->decimal('percent', 5, 2)->default(0);
            $table->unsignedTinyInteger('numberOfStars')->default(5);
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
