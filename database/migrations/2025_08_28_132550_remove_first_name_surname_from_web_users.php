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
            // Check if columns exist before trying to drop them
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
            // Add the columns back in the down method
            $table->string('first_name')->after('id');
            $table->string('surname')->nullable()->after('first_name');
        });
    }
};
