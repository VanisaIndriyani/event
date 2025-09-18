<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eventId = $request->get('event_id');
        $query = EventFormField::with('event');
        
        // Filter by event
        if ($eventId) {
            $query->where('event_id', $eventId);
        }
        
        // Filter by field type
        if ($request->filled('type')) {
            $query->where('field_type', $request->type);
        }
        
        // Filter by required status
        if ($request->filled('required')) {
            $query->where('is_required', $request->required);
        }
        
        // Filter by active status
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }
        
        $formFields = $query->ordered()->paginate(15);
        
        // Preserve query parameters in pagination links
        $formFields->appends($request->query());
        
        $events = Event::where('is_active', true)->get();
        
        return view('admin.form-fields.index', compact('formFields', 'events', 'eventId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $events = Event::where('is_active', true)->get();
        $fieldTypes = EventFormField::getFieldTypes();
        $selectedEventId = $request->get('event_id');
        
        return view('admin.form-fields.create', compact('events', 'fieldTypes', 'selectedEventId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        // Custom validation untuk field_name unique per event
        $validator->after(function ($validator) use ($request) {
            $exists = EventFormField::where('event_id', $request->event_id)
                             ->where('field_name', $request->field_name)
                             ->exists();
            if ($exists) {
                $validator->errors()->add('field_name', 'Field name sudah digunakan untuk event ini.');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        // Set default sort_order jika tidak diisi
        $data = $request->all();
        if (!isset($data['sort_order']) || $data['sort_order'] === null) {
            $maxOrder = EventFormField::where('event_id', $request->event_id)->max('sort_order');
            $data['sort_order'] = ($maxOrder ?? 0) + 1;
        }
        
        EventFormField::create($data);
        
        return redirect()->route('admin.form-fields.index', ['event_id' => $request->event_id])
                        ->with('success', 'Form field berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventFormField $formField)
    {
        $formField->load('event');
        return view('admin.form-fields.show', compact('formField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventFormField $formField)
    {
        $events = Event::where('is_active', true)->get();
        $fieldTypes = EventFormField::getFieldTypes();
        
        return view('admin.form-fields.edit', compact('formField', 'events', 'fieldTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventFormField $formField)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        // Custom validation untuk field_name unique per event (kecuali record saat ini)
        $validator->after(function ($validator) use ($request, $formField) {
            $exists = EventFormField::where('event_id', $request->event_id)
                             ->where('field_name', $request->field_name)
                             ->where('id', '!=', $formField->id)
                             ->exists();
            if ($exists) {
                $validator->errors()->add('field_name', 'Field name sudah digunakan untuk event ini.');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        $formField->update($request->all());
        
        return redirect()->route('admin.form-fields.index', ['event_id' => $formField->event_id])
                        ->with('success', 'Form field berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventFormField $formField)
    {
        $eventId = $formField->event_id;
        $formField->delete();
        
        return redirect()->route('admin.form-fields.index', ['event_id' => $eventId])
                        ->with('success', 'Form field berhasil dihapus.');
    }
    
    /**
     * Update field order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:form_fields,id',
            'items.*.order' => 'required|integer|min:0'
        ]);
        
        foreach ($request->items as $item) {
            EventFormField::where('id', $item['id'])->update(['sort_order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}
