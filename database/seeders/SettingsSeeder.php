<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default payment sections
        $defaultPaymentSections = [
            [
                'name' => 'Bank Transfer',
                'number' => '1234567890',
                'account_name' => 'Event Manager System',
                'enabled' => true
            ],
            [
                'name' => 'E-Wallet (OVO, GoPay, DANA)',
                'number' => '081234567890',
                'account_name' => 'Event Manager',
                'enabled' => true
            ],
            [
                'name' => 'QRIS',
                'number' => '',
                'account_name' => 'Event Manager System',
                'enabled' => true
            ],
            [
                'name' => 'Cash Payment',
                'number' => '',
                'account_name' => 'Pembayaran Tunai',
                'enabled' => true
            ]
        ];

        Settings::updateOrCreate(
            ['key' => 'payment_sections'],
            [
                'value' => json_encode($defaultPaymentSections),
                'type' => 'json',
                'description' => 'Payment sections configuration for registration form'
            ]
        );

        // Default QR code URL (empty initially)
        Settings::updateOrCreate(
            ['key' => 'qr_code_url'],
            [
                'value' => null,
                'type' => 'string',
                'description' => 'QR code image URL for payments'
            ]
        );
    }
}
