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
            $table->date('dateOfSale')->nullable();
            $table->decimal('percent', 5, 2)->nullable();
            $table->integer('numberOfSale')->default(0);
            $table->string('reference')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('numberOfStars')->default(5); // ⭐
            $table->enum('sexes', ['Hommes', 'Femmes', 'Enfants']);   // sexe ciblé
            $table->boolean('is_new')->default(false);         // Nouveautés
            $table->boolean('is_trending')->default(false);    // Tendances
            $table->boolean('is_promo')->default(false);       // Promotions
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
