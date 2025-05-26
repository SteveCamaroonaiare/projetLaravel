<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_color_variants_to_products_table.php
        public function up()
        {
            Schema::table('products', function (Blueprint $table) {
                $table->json('color_variants')->nullable()->after('image');
            });
        }

        public function down()
        {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('color_variants');
            });
        }
};
