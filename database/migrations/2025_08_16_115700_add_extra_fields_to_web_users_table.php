<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('web_users', function (Blueprint $table) {
            if (!Schema::hasColumn('web_users', 'personal_id')) {
                $table->string('personal_id')->nullable();
            }
            if (!Schema::hasColumn('web_users', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('web_users', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_users', function (Blueprint $table) {
            $table->dropColumn(['personal_id', 'birth_date', 'gender']);
        });
    }
};
