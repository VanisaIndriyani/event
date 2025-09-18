<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\EventFormField;
use App\Models\Event;

echo "Checking Event Form Fields for Event ID 1:\n";
echo "==========================================\n";

$event = Event::find(1);
if ($event) {
    echo "Event found: {$event->title}\n";
    echo "Event status: {$event->status}\n";
    echo "Event is_active: " . ($event->is_active ? 'Yes' : 'No') . "\n\n";
    
    $formFields = EventFormField::where('event_id', 1)->get();
    echo "Total form fields for event 1: " . $formFields->count() . "\n\n";
    
    if ($formFields->count() > 0) {
        foreach ($formFields as $field) {
            echo "ID: {$field->id}\n";
            echo "Field Name: {$field->field_name}\n";
            echo "Field Label: {$field->field_label}\n";
            echo "Field Type: {$field->field_type}\n";
            echo "Is Active: " . ($field->is_active ? 'Yes' : 'No') . "\n";
            echo "Is Required: " . ($field->is_required ? 'Yes' : 'No') . "\n";
            echo "Sort Order: {$field->sort_order}\n";
            echo "---\n";
        }
    } else {
        echo "No form fields found for event 1.\n";
    }
    
    echo "\nChecking activeFormFields relationship:\n";
    $activeFields = $event->activeFormFields()->get();
    echo "Active form fields count: " . $activeFields->count() . "\n";
    
} else {
    echo "Event with ID 1 not found.\n";
}

echo "\nChecking all events:\n";
$events = Event::all();
foreach ($events as $evt) {
    echo "Event ID: {$evt->id}, Title: {$evt->title}, Status: {$evt->status}, Active: " . ($evt->is_active ? 'Yes' : 'No') . "\n";
}