<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_vip')) {
                $table->boolean('is_vip')->default(false)->after('active');
            }
            if (!Schema::hasColumn('products', 'is_best_selling')) {
                $table->boolean('is_best_selling')->default(false)->after('is_vip');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_best_selling');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_vip')) {
                $table->dropColumn('is_vip');
            }
            if (Schema::hasColumn('products', 'is_best_selling')) {
                $table->dropColumn('is_best_selling');
            }
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
