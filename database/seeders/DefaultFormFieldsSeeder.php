<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventFormField;

class DefaultFormFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all events that don't have form fields yet
        $eventsWithoutFields = Event::whereDoesntHave('formFields')->get();
        
        foreach ($eventsWithoutFields as $event) {
            $this->createDefaultFields($event);
        }
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
                'field_type' => 'text',
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
                'field_name' => 'usia',
                'field_label' => 'Usia',
                'field_type' => 'number',
                'is_required' => false,
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'field_name' => 'foto_profil',
                'field_label' => 'Foto Profil',
                'field_type' => 'file',
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