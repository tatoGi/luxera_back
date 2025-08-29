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
        // Copy data from first_name and surname to fullname
        if (Schema::hasColumn('web_users', 'first_name') && Schema::hasColumn('web_users', 'surname')) {
            DB::update("UPDATE web_users SET fullname = CONCAT(first_name, ' ', surname) WHERE surname IS NOT NULL AND surname != ''");
            DB::update("UPDATE web_users SET fullname = first_name WHERE fullname IS NULL OR fullname = ''");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data migration, no need to reverse it
        // The fullname column will be dropped by the previous migration
    }
};
