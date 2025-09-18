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
        Schema::create('event_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // jumlah pembayaran
            $table->enum('payment_method', ['transfer', 'cash', 'ewallet'])->default('transfer');
            $table->string('payment_proof')->nullable(); // path file bukti pembayaran
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // catatan dari admin
            $table->timestamp('paid_at')->nullable(); // waktu pembayaran
            $table->timestamp('verified_at')->nullable(); // waktu verifikasi admin
            $table->foreignId('verified_by')->nullable()->constrained('users'); // admin yang verifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_payments');
    }
};
