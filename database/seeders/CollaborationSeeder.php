<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Collaboration;

class CollaborationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collaborations = [
            [
                'name' => 'PT Teknologi Maju',
                'organization' => 'Perusahaan Teknologi',
                'email' => 'info@teknologimaju.com',
                'phone' => '081234567890',
                'collaboration_type' => 'sponsorship',
                'budget' => '50000000-100000000',
                'timeline' => '3-6 months',
                'services' => json_encode(['Event Planning', 'Marketing', 'Technical Support']),
                'event_description' => 'Peluncuran produk teknologi terbaru dengan target audience developer dan startup',
                'additional_info' => 'Membutuhkan venue dengan kapasitas 500 orang dan fasilitas live streaming',
                'status' => 'pending'
            ],
            [
                'name' => 'Universitas Indonesia',
                'organization' => 'Institusi Pendidikan',
                'email' => 'events@ui.ac.id',
                'phone' => '081987654321',
                'collaboration_type' => 'partnership',
                'budget' => '10000000-25000000',
                'timeline' => '1-3 months',
                'services' => json_encode(['Event Planning', 'Catering']),
                'event_description' => 'Seminar nasional tentang inovasi teknologi untuk mahasiswa',
                'additional_info' => 'Event akan diselenggarakan di kampus dengan peserta 200 mahasiswa',
                'status' => 'approved'
            ],
            [
                'name' => 'CV Kreatif Nusantara',
                'organization' => 'Agensi Kreatif',
                'email' => 'hello@kreatifnusantara.id',
                'phone' => '082345678901',
                'collaboration_type' => 'vendor',
                'budget' => '25000000-50000000',
                'timeline' => '1-3 months',
                'services' => json_encode(['Photography', 'Videography', 'Social Media']),
                'event_description' => 'Workshop fotografi dan videografi untuk content creator',
                'additional_info' => 'Memerlukan studio foto dan peralatan profesional',
                'status' => 'pending'
            ],
            [
                'name' => 'Yayasan Peduli Lingkungan',
                'organization' => 'Organisasi Non-Profit',
                'email' => 'contact@pedulilingkungan.org',
                'phone' => '083456789012',
                'collaboration_type' => 'partnership',
                'budget' => '5000000-10000000',
                'timeline' => '1-3 months',
                'services' => json_encode(['Event Planning', 'Volunteer Management']),
                'event_description' => 'Kampanye kesadaran lingkungan dan penanaman pohon',
                'additional_info' => 'Event outdoor dengan target 100 peserta volunteer',
                'status' => 'approved'
            ],
            [
                'name' => 'Komunitas Startup Jakarta',
                'organization' => 'Komunitas',
                'email' => 'admin@startupjkt.com',
                'phone' => '084567890123',
                'collaboration_type' => 'sponsorship',
                'budget' => '100000000+',
                'timeline' => '6+ months',
                'services' => json_encode(['Event Planning', 'Marketing', 'Networking', 'Technical Support']),
                'event_description' => 'Startup pitch competition dan networking event tahunan',
                'additional_info' => 'Event besar dengan 1000+ peserta, membutuhkan venue premium dan live streaming',
                'status' => 'pending'
            ]
        ];

        foreach ($collaborations as $collaboration) {
            Collaboration::create($collaboration);
        }
    }
}
