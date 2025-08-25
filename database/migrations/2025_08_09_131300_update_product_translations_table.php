<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            // Rename 'style' column to 'brand' since that's what we need
            if (Schema::hasColumn('product_translations', 'style')) {
                $table->renameColumn('style', 'brand');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_translations', function (Blueprint $table) {
            // Rename 'brand' back to 'style' to reverse the change
            if (Schema::hasColumn('product_translations', 'brand')) {
                $table->renameColumn('brand', 'style');
            }
        });
    }
}
