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
        Schema::create('post_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->string('attribute_key');
            $table->longText('attribute_value')->nullable();
            $table->string('locale', 5)->nullable(); // null for non-translatable attributes
            $table->timestamps();
            
            $table->index(['post_id', 'attribute_key']);
            $table->index(['post_id', 'attribute_key', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_attributes');
    }
};
