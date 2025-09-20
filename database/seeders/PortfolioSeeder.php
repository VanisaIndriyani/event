<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Portfolio;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil event yang sudah ada
        $events = Event::all();
        
        if ($events->count() > 0) {
            // Buat direktori portfolio_images jika belum ada
            if (!Storage::disk('public')->exists('portfolio_images')) {
                Storage::disk('public')->makeDirectory('portfolio_images');
            }
            
            // Sample portfolio data
            $portfolios = [
                [
                    'title' => 'Business Networking Event Portfolio',
                    'description' => 'Dokumentasi lengkap acara networking bisnis yang sukses dengan partisipasi lebih dari 100 peserta dari berbagai industri.',
                    'status' => 'published',
                    'images' => ['portfolio1_1.jpg', 'portfolio1_2.jpg', 'portfolio1_3.jpg']
                ],
                [
                    'title' => 'Tech Conference 2024 Portfolio',
                    'description' => 'Galeri foto dan dokumentasi konferensi teknologi terbesar tahun ini dengan pembicara internasional.',
                    'status' => 'published', 
                    'images' => ['portfolio2_1.jpg', 'portfolio2_2.jpg']
                ],
                [
                    'title' => 'Workshop Digital Marketing Portfolio',
                    'description' => 'Dokumentasi workshop digital marketing intensif dengan praktik langsung dan studi kasus nyata.',
                    'status' => 'draft',
                    'images' => ['portfolio3_1.jpg', 'portfolio3_2.jpg', 'portfolio3_3.jpg', 'portfolio3_4.jpg']
                ]
            ];
            
            foreach ($portfolios as $index => $portfolioData) {
                // Pilih event secara acak
                $event = $events->random();
                
                // Buat file gambar dummy
                $imageFiles = [];
                foreach ($portfolioData['images'] as $imageName) {
                    $imagePath = 'portfolio_images/' . $imageName;
                    
                    // Buat file gambar dummy (1x1 pixel PNG)
                    $dummyImageContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==');
                    Storage::disk('public')->put($imagePath, $dummyImageContent);
                    
                    $imageFiles[] = $imagePath;
                }
                
                Portfolio::create([
                    'event_id' => $event->id,
                    'title' => $portfolioData['title'],
                    'description' => $portfolioData['description'],
                    'status' => $portfolioData['status'],
                    'images' => json_encode($imageFiles)
                ]);
            }
            
            $this->command->info('Portfolio seeder completed successfully!');
        } else {
            $this->command->warn('No events found. Please run EventSeeder first.');
        }
    }
}
