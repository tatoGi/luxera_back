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
        Schema::table('web_users', function (Blueprint $table) {
            if (Schema::hasColumn('web_users', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('web_users', 'surname')) {
                $table->dropColumn('surname');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('surname')->nullable()->after('first_name');
            
            // Note: Data will be lost when rolling back as we can't recover the split names
        });
    }
};
