<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Portfolio;
use Illuminate\Support\Str;

class CreatePortfoliosFromCompletedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:create-from-events {--force : Force create portfolios even if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create portfolios from completed events that don\'t have portfolios yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting portfolio creation from completed events...');
        
        // Get completed events that don't have portfolios yet
        $query = Event::where('event_date', '<', now())
            ->where('is_active', true);
            
        if (!$this->option('force')) {
            $query->whereDoesntHave('portfolio');
        }
        
        $completedEvents = $query->get();
        
        if ($completedEvents->isEmpty()) {
            $this->info('No completed events found that need portfolios.');
            return 0;
        }
        
        $this->info("Found {$completedEvents->count()} completed events.");
        
        $created = 0;
        $skipped = 0;
        
        foreach ($completedEvents as $event) {
            // Check if portfolio already exists (for force option)
            if ($this->option('force') && $event->portfolio) {
                if (!$this->confirm("Portfolio already exists for '{$event->title}'. Overwrite?")) {
                    $skipped++;
                    continue;
                }
                // Delete existing portfolio
                $event->portfolio->delete();
            }
            
            try {
                Portfolio::create([
                    'event_id' => $event->id,
                    'title' => $event->title . ' Portfolio',
                    'description' => $event->description ?: 'Portfolio from ' . $event->title . ' event held on ' . $event->event_date->format('F j, Y'),
                    'images' => [], // Empty array, admin can add images later
                    'status' => 'draft' // Created as draft, admin can publish later
                ]);
                
                $created++;
                $this->line("✓ Created portfolio for: {$event->title}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to create portfolio for: {$event->title}");
                $this->error("  Error: {$e->getMessage()}");
            }
        }
        
        $this->info("\nPortfolio creation completed!");
        $this->info("Created: {$created}");
        if ($skipped > 0) {
            $this->info("Skipped: {$skipped}");
        }
        
        if ($created > 0) {
            $this->info("\nNote: Portfolios were created as 'draft'. You can edit and publish them in the admin panel.");
        }
        
        return 0;
    }
}