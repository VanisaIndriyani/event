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
        Schema::table('event_payments', function (Blueprint $table) {
            // Add email tracking fields
            $table->boolean('email_sent')->default(false)->after('verified_by');
            $table->timestamp('email_sent_at')->nullable()->after('email_sent');
            $table->foreignId('email_sent_by')->nullable()->constrained('users')->after('email_sent_at');
            
            // Update payment status enum to include 'lunas'
            $table->enum('payment_status', ['pending', 'lunas', 'verified', 'rejected'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_payments', function (Blueprint $table) {
            $table->dropForeign(['email_sent_by']);
            $table->dropColumn(['email_sent', 'email_sent_at', 'email_sent_by']);
            
            // Revert payment status enum
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending')->change();
        });
    }
};