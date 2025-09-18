<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventRegistration;
use App\Models\EventRegistrationData;
use App\Models\EventFormField;
use App\Models\EventPayment;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;
use Faker\Factory as Faker;

class EventRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get some users and events
        $users = User::where('role', 'user')->take(15)->get();
        $events = Event::with('formFields')->take(5)->get();
        
        if ($users->isEmpty() || $events->isEmpty()) {
            $this->command->info('No users or events found. Please seed users and events first.');
            return;
        }
        
        $statuses = ['pending', 'confirmed', 'cancelled'];
        $paymentMethods = ['bank_transfer', 'cash', 'e_wallet', 'qris'];
        $paymentStatuses = ['pending', 'verified', 'rejected'];
        
        $registrationCount = 0;
        
        // Create sample registrations
        foreach ($users as $user) {
            // Each user registers for 1-3 random events
            $userEvents = $events->random(rand(1, 3));
            
            foreach ($userEvents as $event) {
                // Check if registration already exists to avoid duplicates
                $existingRegistration = EventRegistration::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->first();
                
                if (!$existingRegistration) {
                    $registrationStatus = $statuses[array_rand($statuses)];
                    $registeredAt = Carbon::now()->subDays(rand(1, 30));
                    
                    // Create registration
                    $registration = EventRegistration::create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'status' => $registrationStatus,
                        'notes' => $faker->optional(0.3)->sentence(),
                        'registered_at' => $registeredAt
                    ]);
                    
                    $registrationCount++;
                    
                    // Create registration form data
                    $this->createRegistrationFormData($registration, $event, $faker);
                    
                    // Create payment data for paid events
                    if ($event->price > 0) {
                        $this->createPaymentData($registration, $event, $faker, $paymentMethods, $paymentStatuses);
                    }
                }
            }
        }
        
        $this->command->info("Event registrations seeded successfully! Created {$registrationCount} registrations.");
    }
    
    /**
     * Create registration form data
     */
    private function createRegistrationFormData($registration, $event, $faker)
    {
        $formFields = $event->formFields ?? collect();
        
        foreach ($formFields as $field) {
            $value = $this->generateFieldValue($field, $faker);
            
            if ($value !== null) {
                EventRegistrationData::create([
                    'event_registration_id' => $registration->id,
                    'event_form_field_id' => $field->id,
                    'field_value' => $value,
                    'file_path' => $field->field_type === 'file' ? 'uploads/sample_file.pdf' : null
                ]);
            }
        }
    }
    
    /**
     * Create payment data
     */
    private function createPaymentData($registration, $event, $faker, $paymentMethods, $paymentStatuses)
    {
        $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
        $paidAt = $registration->registered_at->addMinutes(rand(10, 120));
        
        $payment = EventPayment::create([
            'event_registration_id' => $registration->id,
            'amount' => $event->price,
            'payment_method' => $paymentMethods[array_rand($paymentMethods)],
            'payment_proof' => 'uploads/payment_proofs/sample_proof_' . $registration->id . '.jpg',
            'payment_status' => $paymentStatus,
            'admin_notes' => $paymentStatus === 'rejected' ? $faker->sentence() : null,
            'paid_at' => $paidAt,
            'verified_at' => $paymentStatus === 'verified' ? $paidAt->addHours(rand(1, 24)) : null,
            'verified_by' => $paymentStatus === 'verified' ? User::where('role', 'admin')->first()?->id : null
        ]);
    }
    
    /**
     * Generate field value based on field type
     */
    private function generateFieldValue($field, $faker)
    {
        switch ($field->field_type) {
            case 'text':
                if (str_contains(strtolower($field->field_name), 'nama')) {
                    return $faker->name();
                } elseif (str_contains(strtolower($field->field_name), 'whatsapp') || str_contains(strtolower($field->field_name), 'phone')) {
                    return $faker->phoneNumber();
                } elseif (str_contains(strtolower($field->field_name), 'instansi') || str_contains(strtolower($field->field_name), 'organisasi')) {
                    return $faker->company();
                } else {
                    return $faker->words(rand(2, 4), true);
                }
                
            case 'email':
                return $faker->email();
                
            case 'number':
                return $faker->numberBetween(1, 100);
                
            case 'textarea':
                if (str_contains(strtolower($field->field_name), 'motivasi')) {
                    return $faker->paragraph(rand(2, 4));
                } else {
                    return $faker->text(200);
                }
                
            case 'select':
                $options = $field->field_options;
                if (is_array($options) && !empty($options)) {
                    return $options[array_rand($options)];
                }
                return $faker->word();
                
            case 'date':
                return $faker->date();
                
            case 'file':
                return 'sample_file.pdf';
                
            default:
                return $faker->word();
        }
    }
}
