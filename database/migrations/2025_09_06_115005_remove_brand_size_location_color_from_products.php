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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'size')) {
                $table->dropColumn('size');
            }
        });

        Schema::table('product_translations', function (Blueprint $table) {
            if (Schema::hasColumn('product_translations', 'brand')) {
                $table->dropColumn('brand');
            }
            if (Schema::hasColumn('product_translations', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('product_translations', 'color')) {
                $table->dropColumn('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'size')) {
                $table->string('size')->nullable();
            }
        });

        Schema::table('product_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('product_translations', 'brand')) {
                $table->string('brand')->nullable();
            }
            if (!Schema::hasColumn('product_translations', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('product_translations', 'color')) {
                $table->string('color')->nullable();
            }
        });
    }
};
