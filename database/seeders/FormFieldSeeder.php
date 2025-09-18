<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\FormField;

class FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        
        foreach ($events as $event) {
            // Basic form fields for all events
            $basicFields = [
                [
                    'field_name' => 'full_name',
                    'field_label' => 'Full Name',
                    'field_type' => 'text',
                    'field_placeholder' => 'Enter your full name',
                    'field_description' => 'Please provide your complete name as it appears on your ID',
                    'is_required' => true,
                    'field_order' => 1,
                    'is_active' => true,
                ],
                [
                    'field_name' => 'email',
                    'field_label' => 'Email Address',
                    'field_type' => 'email',
                    'field_placeholder' => 'Enter your email address',
                    'field_description' => 'We will send confirmation and updates to this email',
                    'is_required' => true,
                    'field_order' => 2,
                    'is_active' => true,
                ],
                [
                    'field_name' => 'phone',
                    'field_label' => 'Phone Number',
                    'field_type' => 'text',
                    'field_placeholder' => 'Enter your phone number',
                    'field_description' => 'Include country code (e.g., +62 for Indonesia)',
                    'is_required' => true,
                    'field_order' => 3,
                    'is_active' => true,
                ],
                [
                    'field_name' => 'organization',
                    'field_label' => 'Organization/Company',
                    'field_type' => 'text',
                    'field_placeholder' => 'Enter your organization or company name',
                    'field_description' => 'Optional: Your workplace or organization',
                    'is_required' => false,
                    'field_order' => 4,
                    'is_active' => true,
                ],
                [
                    'field_name' => 'dietary_requirements',
                    'field_label' => 'Dietary Requirements',
                    'field_type' => 'select',
                    'field_options' => ['None', 'Vegetarian', 'Vegan', 'Halal', 'Gluten-free', 'Other'],
                    'field_placeholder' => null,
                    'field_description' => 'Please let us know if you have any dietary restrictions',
                    'is_required' => false,
                    'field_order' => 5,
                    'is_active' => true,
                ],
                [
                    'field_name' => 'special_notes',
                    'field_label' => 'Special Notes or Requirements',
                    'field_type' => 'textarea',
                    'field_placeholder' => 'Any special requirements or notes...',
                    'field_description' => 'Optional: Any additional information you would like us to know',
                    'is_required' => false,
                    'field_order' => 6,
                    'is_active' => true,
                ],
            ];
            
            foreach ($basicFields as $fieldData) {
                FormField::create(array_merge($fieldData, ['event_id' => $event->id]));
            }
        }
        
        $this->command->info('Form fields seeded successfully! Created basic registration fields for all events.');
    }
}