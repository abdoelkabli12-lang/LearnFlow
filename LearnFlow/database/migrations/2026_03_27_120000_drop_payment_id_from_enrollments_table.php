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
        if (Schema::hasColumn('enrollments', 'payment_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('payment_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('enrollments', 'payment_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }
};
