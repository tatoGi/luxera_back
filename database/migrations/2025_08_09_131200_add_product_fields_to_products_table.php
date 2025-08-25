<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_identify_id')->unique()->after('id');
            $table->string('location')->nullable()->after('category_id');
            $table->string('color')->nullable()->after('location');
            $table->string('size')->nullable()->after('color');
            $table->integer('sort_order')->default(0)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_identify_id', 'location', 'color', 'size', 'sort_order']);
        });
    }
}
