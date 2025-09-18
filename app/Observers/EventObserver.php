<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\EventFormField;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $this->createDefaultFormFields($event);
    }

    /**
     * Create default form fields for new event
     */
    private function createDefaultFormFields(Event $event)
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