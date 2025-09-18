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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama metode pembayaran
            $table->text('description')->nullable(); // deskripsi metode pembayaran
            $table->string('account_number')->nullable(); // nomor rekening/akun
            $table->string('account_name')->nullable(); // nama pemilik rekening
            $table->string('bank_name')->nullable(); // nama bank/provider
            $table->boolean('is_active')->default(true); // status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
