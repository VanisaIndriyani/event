<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventFormController extends Controller
{
    /**
     * Display a listing of the form fields for an event.
     */
    public function index(Event $event)
    {
        $formFields = $event->formFields()->orderBy('sort_order')->get();
        
        return view('admin.events.form-fields.index', compact('event', 'formFields'));
    }

    /**
     * Show the form for creating a new form field.
     */
    public function create(Event $event)
    {
        $fieldTypes = [
            'text' => 'Text',
            'email' => 'Email',
            'number' => 'Number',
            'textarea' => 'Textarea',
            'select' => 'Select',
            'file' => 'File',
            'date' => 'Date'
        ];
        
        return view('admin.events.form-fields.create', compact('event', 'fieldTypes'));
    }

    /**
     * Store a newly created form field in storage.
     */
    public function store(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'field_label' => 'required|string|max:255',
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'is_required' => 'boolean',
            'field_options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if field_name is unique for this event
        $existingField = $event->formFields()->where('field_name', $request->field_name)->first();
        if ($existingField) {
            return redirect()->back()
                ->withErrors(['field_name' => 'Field name already exists for this event.'])
                ->withInput();
        }

        // Get the next order number
        $maxOrder = $event->formFields()->max('sort_order') ?? 0;

        $formField = new EventFormField([
            'event_id' => $event->id,
            'field_label' => $request->field_label,
            'field_name' => $request->field_name,
            'field_type' => $request->field_type,
            'is_required' => $request->boolean('is_required'),
            'field_options' => $request->field_type === 'select' ? $request->field_options : null,
            'sort_order' => $maxOrder + 1,
        ]);

        $formField->save();

        return redirect()->route('admin.events.form-fields.index', $event)
            ->with('success', 'Form field created successfully.');
    }

    /**
     * Show the form for editing the specified form field.
     */
    public function edit(Event $event, EventFormField $formField)
    {
        // Ensure the form field belongs to the event
        if ($formField->event_id !== $event->id) {
            abort(404);
        }

        $fieldTypes = [
            'text' => 'Text',
            'email' => 'Email',
            'number' => 'Number',
            'textarea' => 'Textarea',
            'select' => 'Select',
            'file' => 'File',
            'date' => 'Date'
        ];

        return view('admin.events.form-fields.edit', compact('event', 'formField', 'fieldTypes'));
    }

    /**
     * Update the specified form field in storage.
     */
    public function update(Request $request, Event $event, EventFormField $formField)
    {
        // Ensure the form field belongs to the event
        if ($formField->event_id !== $event->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'field_label' => 'required|string|max:255',
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'is_required' => 'boolean',
            'field_options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if field_name is unique for this event (excluding current field)
        $existingField = $event->formFields()
            ->where('field_name', $request->field_name)
            ->where('id', '!=', $formField->id)
            ->first();
            
        if ($existingField) {
            return redirect()->back()
                ->withErrors(['field_name' => 'Field name already exists for this event.'])
                ->withInput();
        }

        $formField->update([
            'field_label' => $request->field_label,
            'field_name' => $request->field_name,
            'field_type' => $request->field_type,
            'is_required' => $request->boolean('is_required'),
            'field_options' => $request->field_type === 'select' ? $request->field_options : null,
        ]);

        return redirect()->route('admin.events.form-fields.index', $event)
            ->with('success', 'Form field updated successfully.');
    }

    /**
     * Remove the specified form field from storage.
     */
    public function destroy(Event $event, EventFormField $formField)
    {
        // Ensure the form field belongs to the event
        if ($formField->event_id !== $event->id) {
            abort(404);
        }

        $formField->delete();

        return redirect()->route('admin.events.form-fields.index', $event)
            ->with('success', 'Form field deleted successfully.');
    }

    /**
     * Update the order of form fields.
     */
    public function updateOrder(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'field_ids' => 'required|array',
            'field_ids.*' => 'required|integer|exists:event_form_fields,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data.'], 400);
        }

        foreach ($request->field_ids as $index => $fieldId) {
            $formField = EventFormField::where('id', $fieldId)
                ->where('event_id', $event->id)
                ->first();
                
            if ($formField) {
                $formField->update(['sort_order' => $index + 1]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Field order updated successfully.']);
    }
}