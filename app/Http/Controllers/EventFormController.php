<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventFormController extends Controller
{
    public function index(Event $event)
    {
        $formFields = $event->formFields()->ordered()->get();
        return view('admin.events.form-fields.index', compact('event', 'formFields'));
    }

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

    public function store(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255|unique:event_form_fields,field_name,NULL,id,event_id,' . $event->id,
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event->formFields()->create([
            'field_name' => $request->field_name,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'field_options' => $request->field_options,
            'is_required' => $request->boolean('is_required'),
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ]);

        return redirect()->route('admin.events.form-fields.index', $event)
                        ->with('success', 'Form field berhasil ditambahkan.');
    }

    public function edit(Event $event, EventFormField $formField)
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
        return view('admin.events.form-fields.edit', compact('event', 'formField', 'fieldTypes'));
    }

    public function update(Request $request, Event $event, EventFormField $formField)
    {
        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255|unique:event_form_fields,field_name,' . $formField->id . ',id,event_id,' . $event->id,
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,textarea,select,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formField->update([
            'field_name' => $request->field_name,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'field_options' => $request->field_options,
            'is_required' => $request->boolean('is_required'),
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.events.form-fields.index', $event)
                        ->with('success', 'Form field berhasil diupdate.');
    }

    public function destroy(Event $event, EventFormField $formField)
    {
        $formField->delete();
        return redirect()->route('admin.events.form-fields.index', $event)
                        ->with('success', 'Form field berhasil dihapus.');
    }

    public function updateOrder(Request $request, Event $event)
    {
        $orders = $request->input('orders', []);
        
        foreach ($orders as $order) {
            EventFormField::where('id', $order['id'])
                         ->where('event_id', $event->id)
                         ->update(['sort_order' => $order['position']]);
        }

        return response()->json(['success' => true]);
    }
}
