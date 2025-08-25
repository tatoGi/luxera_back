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
        // Add style column to products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('style')->after('price');
        });

        // Remove columns from product_translations table
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model', 'warranty_period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove style column from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('style');
        });

        // Add back the columns to product_translations table
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('brand')->after('description');
            $table->string('model')->after('brand');
            $table->string('warranty_period')->nullable()->after('model');
        });
    }
};
