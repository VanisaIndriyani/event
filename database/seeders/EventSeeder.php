<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Summer Music Festival 2024',
                'description' => 'Join us for an unforgettable summer music festival featuring top artists from around the world. Experience live performances, food trucks, and amazing atmosphere under the stars.',
                'event_date' => Carbon::now()->addDays(30),
                'location' => 'Central Park, Jakarta',
                'max_participants' => 5000,
                'price' => 250000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tech Conference 2024',
                'description' => 'A comprehensive technology conference bringing together industry leaders, developers, and innovators to discuss the latest trends in AI, blockchain, and web development.',
                'event_date' => Carbon::now()->addDays(45),
                'location' => 'Jakarta Convention Center',
                'max_participants' => 1000,
                'price' => 500000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Charity Gala Night',
                'description' => 'An elegant charity gala to raise funds for local orphanages. Enjoy fine dining, live entertainment, and contribute to a meaningful cause.',
                'event_date' => Carbon::now()->addDays(60),
                'location' => 'Grand Ballroom, Hotel Indonesia',
                'max_participants' => 300,
                'price' => 1000000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gaming Tournament Championship',
                'description' => 'The ultimate gaming tournament featuring popular esports titles. Compete for prizes worth millions of rupiah and prove your gaming skills.',
                'event_date' => Carbon::now()->addDays(15),
                'location' => 'Esports Arena, Mall Taman Anggrek',
                'max_participants' => 128,
                'price' => 100000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cooking Workshop: Indonesian Cuisine',
                'description' => 'Learn to cook authentic Indonesian dishes from professional chefs. Hands-on workshop includes ingredients and recipe booklet.',
                'event_date' => Carbon::now()->addDays(20),
                'location' => 'Culinary Institute, Senayan',
                'max_participants' => 50,
                'price' => 350000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Photography Exhibition',
                'description' => 'A stunning photography exhibition showcasing works from local and international photographers. Free admission for art enthusiasts.',
                'event_date' => Carbon::now()->addDays(10),
                'location' => 'National Gallery, Jakarta',
                'max_participants' => null,
                'price' => 0,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Business Networking Event',
                'description' => 'Connect with fellow entrepreneurs, investors, and business professionals. Expand your network and discover new opportunities.',
                'event_date' => Carbon::now()->addDays(25),
                'location' => 'Ritz Carlton, Jakarta',
                'max_participants' => 200,
                'price' => 750000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Yoga & Wellness Retreat',
                'description' => 'A peaceful yoga and wellness retreat in the mountains. Includes accommodation, healthy meals, and guided meditation sessions.',
                'event_date' => Carbon::now()->addDays(40),
                'location' => 'Puncak Resort, Bogor',
                'max_participants' => 80,
                'price' => 1200000,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Book Fair & Literary Festival',
                'description' => 'A celebration of literature featuring book sales, author meet-and-greets, poetry readings, and writing workshops.',
                'event_date' => Carbon::now()->addDays(35),
                'location' => 'Taman Ismail Marzuki, Jakarta',
                'max_participants' => null,
                'price' => 0,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Young entrepreneurs compete for funding and mentorship opportunities. Witness innovative ideas and groundbreaking solutions.',
                'event_date' => Carbon::now()->addDays(50),
                'location' => 'Innovation Hub, BSD City',
                'max_participants' => 100,
                'price' => 150000,
                'is_active' => false,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}