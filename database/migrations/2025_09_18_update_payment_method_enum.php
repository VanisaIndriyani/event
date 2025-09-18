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
        // Update the enum values for payment_method column
        DB::statement("ALTER TABLE event_payments MODIFY COLUMN payment_method ENUM('bank_transfer', 'e_wallet', 'qris', 'cash') DEFAULT 'bank_transfer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE event_payments MODIFY COLUMN payment_method ENUM('transfer', 'cash', 'ewallet') DEFAULT 'transfer'");
    }
};