<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Bank Transfer',
                'description' => 'Transfer melalui rekening bank',
                'account_number' => '1234567890',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'Bank Mandiri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'E-Wallet (GoPay)',
                'description' => 'Pembayaran melalui GoPay',
                'account_number' => '081234567890',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'GoPay',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'E-Wallet (OVO)',
                'description' => 'Pembayaran melalui OVO',
                'account_number' => '081234567890',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'OVO',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'E-Wallet (DANA)',
                'description' => 'Pembayaran melalui DANA',
                'account_number' => '081234567890',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'DANA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Transfer (BCA)',
                'description' => 'Transfer melalui rekening BCA',
                'account_number' => '0987654321',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'Bank Central Asia (BCA)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Transfer (BNI)',
                'description' => 'Transfer melalui rekening BNI',
                'account_number' => '1122334455',
                'account_name' => 'Epic Events Indonesia',
                'bank_name' => 'Bank Negara Indonesia (BNI)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert payment methods
        DB::table('payment_methods')->insert($paymentMethods);

        $this->command->info('Payment methods seeded successfully!');
    }
}