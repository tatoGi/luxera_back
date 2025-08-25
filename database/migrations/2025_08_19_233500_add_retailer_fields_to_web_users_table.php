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
            if (!Schema::hasColumn('web_users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('web_users', 'is_retailer')) {
                $table->boolean('is_retailer')->default(false)->after('gender');
            }
            if (!Schema::hasColumn('web_users', 'retailer_status')) {
                $table->string('retailer_status')->nullable()->after('is_retailer'); // pending, approved, rejected
            }
            if (!Schema::hasColumn('web_users', 'retailer_requested_at')) {
                $table->timestamp('retailer_requested_at')->nullable()->after('retailer_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_users', function (Blueprint $table) {
            $drops = [];
            if (Schema::hasColumn('web_users', 'is_retailer')) $drops[] = 'is_retailer';
            if (Schema::hasColumn('web_users', 'retailer_status')) $drops[] = 'retailer_status';
            if (Schema::hasColumn('web_users', 'retailer_requested_at')) $drops[] = 'retailer_requested_at';
            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
            // do not drop phone to avoid data loss
        });
    }
};
