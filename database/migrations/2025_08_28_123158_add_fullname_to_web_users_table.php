<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('web_users', function (Blueprint $table) {
            // Add the new fullname column
            $table->string('fullname')->after('id')->nullable();
        });
        
        // Migrate existing data
        if (Schema::hasColumn('web_users', 'first_name') && Schema::hasColumn('web_users', 'surname')) {
            DB::statement("UPDATE web_users SET fullname = CONCAT(first_name, ' ', surname) WHERE surname IS NOT NULL AND surname != ''");
            DB::statement("UPDATE web_users SET fullname = first_name WHERE fullname IS NULL OR fullname = ''");
        }
        
        // Make fullname not nullable
        Schema::table('web_users', function (Blueprint $table) {
            $table->string('fullname')->nullable(false)->change();
        });
        
        // Remove old columns if they exist
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
        // Add back the old columns
        Schema::table('web_users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('surname')->nullable()->after('first_name');
        });
        
        // Split fullname back to first_name and surname
        DB::statement("UPDATE web_users SET first_name = SUBSTRING_INDEX(fullname, ' ', 1)");
        DB::statement("UPDATE web_users SET surname = NULLIF(TRIM(SUBSTRING(fullname, LENGTH(SUBSTRING_INDEX(fullname, ' ', 1)) + 1)), '')");
        
        // Drop the fullname column
        Schema::table('web_users', function (Blueprint $table) {
            $table->dropColumn('fullname');
        });
    }
};
