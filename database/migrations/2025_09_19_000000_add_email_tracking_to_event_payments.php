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
            $table->boolean('email_sent')->default(false)->after('verified_by');
            $table->timestamp('email_sent_at')->nullable()->after('email_sent');
            $table->unsignedBigInteger('email_sent_by')->nullable()->after('email_sent_at');
            
            $table->foreign('email_sent_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Update payment_status enum to include 'lunas'
        DB::statement("ALTER TABLE event_payments MODIFY COLUMN payment_status ENUM('pending', 'lunas', 'verified', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_payments', function (Blueprint $table) {
            $table->dropForeign(['email_sent_by']);
            $table->dropColumn(['email_sent', 'email_sent_at', 'email_sent_by']);
        });
        
        // Revert payment_status enum
        DB::statement("ALTER TABLE event_payments MODIFY COLUMN payment_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'");
    }
};