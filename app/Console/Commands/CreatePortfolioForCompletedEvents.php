<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Portfolio;
use Carbon\Carbon;

class CreatePortfolioForCompletedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:create-for-completed-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create portfolio entries for events that have ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for completed events without portfolios...');
        
        // Find events that have ended but don't have portfolios yet
        $completedEvents = Event::where('event_date', '<', Carbon::now())
            ->where('is_active', true)
            ->whereDoesntHave('portfolio')
            ->get();
            
        if ($completedEvents->isEmpty()) {
            $this->info('No completed events found that need portfolio creation.');
            return 0;
        }
        
        $this->info("Found {$completedEvents->count()} completed events without portfolios.");
        
        $created = 0;
        
        foreach ($completedEvents as $event) {
            try {
                Portfolio::create([
                    'event_id' => $event->id,
                    'title' => $event->title . ' - Portfolio',
                    'description' => 'Portfolio untuk event: ' . $event->title . '. Event ini telah selesai pada ' . $event->event_date->format('d M Y') . '.',
                    'images' => $event->images ?? [],
                    'status' => 'draft'
                ]);
                
                $created++;
                $this->line("✓ Created portfolio for: {$event->title}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to create portfolio for {$event->title}: {$e->getMessage()}");
            }
        }
        
        $this->info("\nPortfolio creation completed. Created {$created} portfolios.");
        
        return 0;
    }
}
