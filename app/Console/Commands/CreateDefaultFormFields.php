<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventFormField;

class CreateDefaultFormFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'form-fields:create-default {--event-id= : Specific event ID to create fields for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default form fields for events that don\'t have any form fields';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->option('event-id');
        
        if ($eventId) {
            $event = Event::find($eventId);
            if (!$event) {
                $this->error('Event not found!');
                return 1;
            }
            
            if ($event->formFields()->count() > 0) {
                $this->info('Event already has form fields.');
                return 0;
            }
            
            $this->createDefaultFields($event);
            $this->info('Default form fields created for event: ' . $event->title);
        } else {
            $eventsWithoutFields = Event::whereDoesntHave('formFields')->get();
            
            if ($eventsWithoutFields->count() === 0) {
                $this->info('All events already have form fields.');
                return 0;
            }
            
            $this->info('Found ' . $eventsWithoutFields->count() . ' events without form fields.');
            
            foreach ($eventsWithoutFields as $event) {
                $this->createDefaultFields($event);
                $this->line('Created default fields for: ' . $event->title);
            }
            
            $this->info('Completed creating default form fields for all events.');
        }
        
        return 0;
    }
    
    /**
     * Create default form fields for an event
     */
    private function createDefaultFields(Event $event)
    {
        $defaultFields = [
            [
                'field_name' => 'nama_lengkap',
                'field_label' => 'Nama Lengkap',
                'field_type' => 'text',
                'is_required' => true,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'field_name' => 'email',
                'field_label' => 'Email',
                'field_type' => 'email',
                'is_required' => true,
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'field_name' => 'no_whatsapp',
                'field_label' => 'Nomor WhatsApp',
                'field_type' => 'text',
                'is_required' => true,
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'field_name' => 'instansi',
                'field_label' => 'Asal Instansi/Organisasi',
                'field_type' => 'text',
                'is_required' => false,
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'field_name' => 'motivasi',
                'field_label' => 'Motivasi Mengikuti Event',
                'field_type' => 'textarea',
                'is_required' => false,
                'sort_order' => 5,
                'is_active' => true
            ]
        ];
        
        foreach ($defaultFields as $fieldData) {
            EventFormField::create(array_merge($fieldData, [
                'event_id' => $event->id
            ]));
        }
    }
}
