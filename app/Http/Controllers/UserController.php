<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Merchandise;
use App\Models\EventRegistration;
use App\Models\EventRegistrationData;
use App\Models\EventPayment;
use App\Models\Collaboration;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function home()
    {
        $featuredEvents = Event::where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(6)
            ->get();
            
        $featuredMerchandise = Merchandise::where('is_active', true)
            ->take(4)
            ->get();

        return view('home', compact('featuredEvents', 'featuredMerchandise'));
    }

    public function dashboard()
    {
        $userId = Auth::id();
        
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->where('is_active', true)
            ->orderBy('event_date')
            ->take(3)
            ->get();
            
        $userRegistrations = EventRegistration::where('user_id', $userId)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();
            
        // Calculate stats
        $registeredEvents = EventRegistration::where('user_id', $userId)->count();
        $merchandiseOrders = 0; // Placeholder for merchandise orders
        $collaborations = Collaboration::where('email', Auth::user()->email)->count();
        $totalPoints = $registeredEvents * 10; // Simple point system
        
        // Recent activities
        $recentActivities = [];
        
        // Add recent registrations to activities
        foreach($userRegistrations->take(3) as $registration) {
            $recentActivities[] = [
                'icon' => 'fas fa-calendar-check',
                'title' => 'Registered for ' . $registration->event->title,
                'time' => $registration->created_at->diffForHumans()
            ];
        }
        
        // Add recent collaborations to activities
        $recentCollabs = Collaboration::where('email', Auth::user()->email)
            ->latest()
            ->take(2)
            ->get();
            
        foreach($recentCollabs as $collab) {
            $recentActivities[] = [
                'icon' => 'fas fa-handshake',
                'title' => 'Collaboration request: ' . $collab->name,
                'time' => $collab->created_at->diffForHumans()
            ];
        }
        
        // Sort activities by time
        usort($recentActivities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        $recentActivities = array_slice($recentActivities, 0, 5);

        return view('user.dashboard', compact(
            'upcomingEvents', 
            'userRegistrations', 
            'registeredEvents', 
            'merchandiseOrders', 
            'collaborations', 
            'totalPoints', 
            'recentActivities'
        ));
    }

    public function events(Request $request)
    {
        $query = Event::where('is_active', true);
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }
        
        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        // Filter by price range
        if ($request->filled('price')) {
            $priceRange = $request->price;
            if ($priceRange === 'free') {
                $query->where(function($q) {
                    $q->where('price', 0)->orWhereNull('price');
                });
            } elseif ($priceRange === '0-100000') {
                $query->where('price', '>', 0)->where('price', '<=', 100000);
            } elseif ($priceRange === '100000-500000') {
                $query->whereBetween('price', [100000, 500000]);
            } elseif ($priceRange === '500000+') {
                $query->where('price', '>', 500000);
            }
        }
        
        // Filter by status (using existing status column instead of category)
        if ($request->filled('category')) {
            // Map category filter to status or title search
            $category = $request->category;
            if (in_array($category, ['upcoming', 'ongoing', 'completed', 'cancelled'])) {
                $query->where('status', $category);
            } else {
                // Search in title for category-like terms
                $query->where('title', 'like', '%' . $category . '%');
            }
        }
        
        $events = $query->orderBy('event_date')->paginate(12);

        return view('user.events', compact('events'));
    }

    public function eventDetail($id)
    {
        $event = Event::with('activeFormFields')->findOrFail($id);
        $isRegistered = EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $id)
            ->exists();

        return view('user.event-detail', compact('event', 'isRegistered'));
    }

    public function registerEvent(Request $request, $id)
    {
        \Log::info('Registration attempt started for event ID: ' . $id);
        \Log::info('Request data: ', $request->all());
        
        $event = Event::with('activeFormFields')->findOrFail($id);
        \Log::info('Event found: ' . $event->title);
        
        // Check if already registered
        $existingRegistration = EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $id)
            ->first();
            
        if ($existingRegistration) {
            return back()->with('error', 'Anda sudah terdaftar untuk event ini.');
        }
        
        // Check if event is full
        $currentRegistrations = EventRegistration::where('event_id', $id)->count();
        if ($event->max_participants && $currentRegistrations >= $event->max_participants) {
            return back()->with('error', 'Event sudah penuh.');
        }

        // Validate dynamic form fields
        $rules = [];
        $formData = [];
        
        foreach ($event->activeFormFields as $field) {
            $fieldName = 'form_' . $field->field_name;
            
            if ($field->is_required) {
                if ($field->field_type === 'file') {
                    $rules[$fieldName] = 'required|file|max:2048';
                } else {
                    $rules[$fieldName] = 'required';
                }
            } else {
                if ($field->field_type === 'file') {
                    $rules[$fieldName] = 'nullable|file|max:2048';
                }
            }
            
            if ($field->field_type === 'email') {
                $rules[$fieldName] = ($rules[$fieldName] ?? 'nullable') . '|email';
            }
        }
        
        // Add payment validation if event has price
        if ($event->price > 0) {
            $rules['payment_method'] = 'required|string|in:bank_transfer,e_wallet,qris,cash';
            $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        
        \Log::info('Validation rules: ', $rules);
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            \Log::error('Validation failed: ', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali data yang Anda masukkan. Ada beberapa field yang belum diisi dengan benar.');
        }
        
        \Log::info('Validation passed, creating registration...');

        // Create registration
        $registration = EventRegistration::create([
            'user_id' => Auth::id(),
            'event_id' => $id,
            'status' => $event->price > 0 ? 'pending' : 'confirmed',
            'registered_at' => now(),
        ]);
        
        // Save dynamic form data
        foreach ($event->activeFormFields as $field) {
            $fieldName = 'form_' . $field->field_name;
            $value = $request->input($fieldName);
            
            if ($field->field_type === 'file' && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('event_files', $filename, 'public');
                $value = $path;
            }
            
            if ($value !== null) {
                EventRegistrationData::create([
                    'event_registration_id' => $registration->id,
                    'event_form_field_id' => $field->id,
                    'field_value' => $value
                ]);
            }
        }
        
        // Handle payment if event has price
        if ($event->price > 0 && $request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'payment_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            
            EventPayment::create([
                'event_registration_id' => $registration->id,
                'amount' => $event->price,
                'payment_method' => $request->input('payment_method'),
                'payment_proof' => $path,
                'payment_status' => 'pending',
                'paid_at' => now()
            ]);
        }

        // Send email notification for registration confirmation
        try {
            \Mail::to($registration->user->email)->send(new \App\Mail\RegistrationConfirmation($registration));
            \Log::info('Email notification sent successfully - Registration ID: ' . $registration->id);
        } catch (\Exception $e) {
            \Log::error('Failed to send email notification: ' . $e->getMessage());
        }

        $message = $event->price > 0 
            ? 'Pendaftaran berhasil! Silakan tunggu verifikasi pembayaran. Email konfirmasi telah dikirim.' 
            : 'Berhasil mendaftar event! Email konfirmasi telah dikirim.';
        
        \Log::info('Registration completed successfully. Redirecting with message: ' . $message);
        return redirect()->route('events')->with('success', $message);
    }

    public function showRegistrationForm($id)
    {
        $event = Event::with('activeFormFields')->findOrFail($id);
        
        // Check if already registered
        $existingRegistration = EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $id)
            ->first();
            
        if ($existingRegistration) {
            return redirect()->route('events')->with('error', 'Anda sudah terdaftar untuk event ini. Silakan cek status pendaftaran Anda di dashboard.');
        }
        
        // Check if event is full
        $currentRegistrations = EventRegistration::where('event_id', $id)->count();
        if ($event->max_participants && $currentRegistrations >= $event->max_participants) {
            return redirect()->route('events')->with('error', 'Maaf, event ini sudah penuh. Silakan pilih event lainnya.');
        }
        
        // Check if event is still available for registration
        if ($event->status === 'completed' || $event->status === 'cancelled') {
            return redirect()->route('events')->with('error', 'Event ini sudah tidak tersedia untuk pendaftaran. Status: ' . ucfirst($event->status));
        }

        // Define field types for the view
        $fieldTypes = [
            'text' => 'Text',
            'email' => 'Email',
            'number' => 'Number',
            'tel' => 'Phone',
            'textarea' => 'Textarea',
            'select' => 'Select',
            'radio' => 'Radio',
            'checkbox' => 'Checkbox',
            'file' => 'File',
            'date' => 'Date',
            'time' => 'Time',
            'datetime-local' => 'DateTime'
        ];

        // Get payment sections from settings
        $paymentSections = Settings::get('payment_sections', []);
        $qrCodeUrl = Settings::getQrCodeUrl();

        return view('user.registration', compact('event', 'fieldTypes', 'paymentSections', 'qrCodeUrl'));
    }

    public function merchandise(Request $request)
    {
        $query = Merchandise::where('is_active', true);
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by price range
        if ($request->filled('price')) {
            $priceRange = $request->price;
            if ($priceRange === 'under-50000') {
                $query->where('price', '<', 50000);
            } elseif ($priceRange === '50000-150000') {
                $query->whereBetween('price', [50000, 150000]);
            } elseif ($priceRange === '150000-300000') {
                $query->whereBetween('price', [150000, 300000]);
            } elseif ($priceRange === '300000+') {
                $query->where('price', '>', 300000);
            }
        }
        
        // Filter by stock availability
        if ($request->filled('stock')) {
            $stockFilter = $request->stock;
            if ($stockFilter === 'available') {
                $query->where('stock', '>', 10);
            } elseif ($stockFilter === 'low') {
                $query->whereBetween('stock', [1, 10]);
            }
        }
        
        $merchandise = $query->orderBy('name')->paginate(12);

        return view('user.merchandise', compact('merchandise'));
    }

    public function portfolio()
    {
        $pastEvents = Event::where('event_date', '<', now())
            ->where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        return view('user.portfolio', compact('pastEvents'));
    }

    public function collab()
    {
        return view('user.collab');
    }

    public function submitCollab(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'collaboration_type' => 'required|string',
        ]);

        Collaboration::create([
            'name' => $request->name,
            'organization' => $request->organization,
            'email' => $request->email,
            'phone' => $request->phone,
            'collaboration_type' => $request->collaboration_type,
            'budget' => $request->budget,
            'timeline' => $request->timeline,
            'services' => $request->services ?? [],
            'event_description' => $request->event_description,
            'additional_info' => $request->additional_info,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Terima kasih! Permintaan kolaborasi Anda telah dikirim. Tim kami akan menghubungi Anda segera.');
    }
}
