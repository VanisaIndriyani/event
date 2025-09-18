<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use App\Models\Merchandise;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\Collaboration;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEvents = Event::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRegistrations = EventRegistration::count();
        $totalMerchandise = Merchandise::count();
        
        $recentRegistrations = EventRegistration::with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();
            
        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        $totalCollaborations = Collaboration::count();
        $pendingCollaborations = Collaboration::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalEvents', 'totalUsers', 'totalRegistrations', 'totalMerchandise',
            'totalCollaborations', 'pendingCollaborations',
            'recentRegistrations', 'upcomingEvents'
        ));
    }

    public function collaborations(Request $request)
    {
        $query = Collaboration::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('organization', 'like', '%' . $search . '%');
            });
        }

        $collaborations = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.collaborations.index', compact('collaborations'));
    }

    public function showCollaboration($id)
    {
        $collaboration = Collaboration::findOrFail($id);
        return view('admin.collaborations.show', compact('collaboration'));
    }

    public function updateCollaborationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,approved,rejected,completed'
        ]);

        $collaboration = Collaboration::findOrFail($id);
        $collaboration->update(['status' => $request->status]);

        return back()->with('success', 'Status kolaborasi berhasil diupdate!');
    }

    public function deleteCollaboration($id)
    {
        $collaboration = Collaboration::findOrFail($id);
        $collaboration->delete();

        return redirect()->route('admin.collaborations.index')
                        ->with('success', 'Data kolaborasi berhasil dihapus!');
    }

    // Event Management
    public function events(Request $request)
    {
        $query = Event::withCount('registrations');
        
        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }
        
        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'upcoming':
                    $query->where('event_date', '>', now());
                    break;
                case 'completed':
                    $query->where('event_date', '<', now());
                    break;
                case 'cancelled':
                    $query->where('is_active', false);
                    break;
            }
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }
        
        $events = $query->orderBy('event_date', 'desc')->paginate(10);
        
        // Preserve query parameters in pagination links
        $events->appends($request->query());
        
        return view('admin.events.index', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = true;
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('events', 'public');
            }
            $data['images'] = $imagePaths;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat!');
    }

    public function showEvent($id)
    {
        $event = Event::withCount('registrations')->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'title', 'description', 'event_date', 'location', 
            'max_participants', 'price'
        ]);
        
        // Handle boolean conversion for is_active
        $data['is_active'] = $request->input('is_active', 0) == 1;
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($event->images && is_array($event->images)) {
                foreach ($event->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            } elseif ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('events', 'public');
            }
            $data['images'] = $imagePaths;
            $data['image'] = null; // Clear old single image field
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate!');
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    // Merchandise Management
    public function merchandise()
    {
        $merchandise = Merchandise::orderBy('name')->paginate(10);
        return view('admin.merchandise.index', compact('merchandise'));
    }

    public function createMerchandise()
    {
        return view('admin.merchandise.create');
    }

    public function storeMerchandise(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = 'active';
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('merchandise', 'public');
        }

        Merchandise::create($data);

        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise berhasil dibuat!');
    }

    public function editMerchandise(Merchandise $merchandise)
    {
        return view('admin.merchandise.edit', compact('merchandise'));
    }

    public function updateMerchandise(Request $request, Merchandise $merchandise)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($merchandise->image) {
                Storage::disk('public')->delete($merchandise->image);
            }
            $data['image'] = $request->file('image')->store('merchandise', 'public');
        }

        $merchandise->update($data);

        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise berhasil diupdate!');
    }

    public function deleteMerchandise(Merchandise $merchandise)
    {
        // Delete image if exists
        if ($merchandise->image) {
            Storage::disk('public')->delete($merchandise->image);
        }
        
        $merchandise->delete();

        return redirect()->route('admin.merchandise.index')->with('success', 'Merchandise berhasil dihapus!');
    }

    // Registration Management
    public function registrations(Request $request)
    {
        $query = EventRegistration::with(['user', 'event', 'payment']);
        
        // Event filter
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('registered_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('registered_at', '<=', $request->date_to);
        }
        
        // Search filter
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhereHas('event', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
        
        $registrations = $query->orderBy('registered_at', 'desc')->paginate(15);
        
        // Preserve query parameters in pagination links
        $registrations->appends($request->query());
        
        // Get all events for filter dropdown
        $events = Event::orderBy('title')->get();
        
        // Get registration statistics
        $stats = [
            'total' => EventRegistration::count(),
            'confirmed' => EventRegistration::where('status', 'confirmed')->count(),
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'cancelled' => EventRegistration::where('status', 'cancelled')->count()
        ];
            
        return view('admin.registrations.index', compact('registrations', 'events', 'stats'));
    }

    public function showRegistration($id)
    {
        $registration = EventRegistration::with(['user', 'event'])->findOrFail($id);
        return view('admin.registrations.show', compact('registration'));
    }

    public function updateRegistrationStatus(Request $request, $id)
    {
        $registration = EventRegistration::with(['user', 'event'])->findOrFail($id);
        $oldStatus = $registration->status;
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);
        
        $registration->update([
            'status' => $request->status
        ]);
        
        // Send email notification when admin approves registration (status changed to confirmed)
        if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
            try {
                // Send approval email to user
                \Mail::to($registration->user->email)->send(new \App\Mail\RegistrationApproved($registration));
                \Log::info('Email approval notification sent successfully - Registration ID: ' . $registration->id);
            } catch (\Exception $e) {
                \Log::error('Failed to send email approval notification: ' . $e->getMessage());
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Status registrasi berhasil diupdate!']);
        }
        
        return back()->with('success', 'Status registrasi berhasil diupdate!');
    }
    
    public function deleteRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        $registration->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Registrasi berhasil dihapus!']);
        }
        
        return back()->with('success', 'Registrasi berhasil dihapus!');
    }
    
    public function exportRegistrations(Request $request)
    {
        $registrations = EventRegistration::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'registrations_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Participant Name',
                'Email',
                'Event Title',
                'Registration Date',
                'Status',
                'Notes'
            ]);
            
            // CSV data
            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->id,
                    $registration->user->name,
                    $registration->user->email,
                    $registration->event->title,
                    $registration->registered_at->format('Y-m-d H:i:s'),
                    ucfirst($registration->status),
                    $registration->notes
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportRegistrationsExcel(Request $request)
    {
        $filename = 'registrations_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new RegistrationsExport, $filename);
    }
    
    public function analytics()
    {
        // Get analytics data
        $totalEvents = Event::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRegistrations = EventRegistration::count();
        $totalMerchandise = Merchandise::count();
        
        // Monthly registrations data
        $monthlyRegistrations = EventRegistration::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Popular events
        $popularEvents = Event::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take(5)
            ->get();
            
        // Recent activity
        $recentActivity = EventRegistration::with(['user', 'event'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.analytics', compact(
            'totalEvents', 'totalUsers', 'totalRegistrations', 'totalMerchandise',
            'monthlyRegistrations', 'popularEvents', 'recentActivity'
        ));
    }

    public function settings()
    {
        // Get current settings
        $paymentSections = Settings::get('payment_sections', [
            ['name' => 'Bank Transfer', 'number' => '1234567890', 'account_name' => 'Event Manager', 'enabled' => true],
            ['name' => 'E-Wallet (OVO, GoPay, DANA)', 'number' => '081234567890', 'account_name' => 'Event Manager', 'enabled' => true],
            ['name' => 'QRIS', 'number' => '', 'account_name' => '', 'enabled' => true],
            ['name' => 'Cash Payment', 'number' => '', 'account_name' => '', 'enabled' => true]
        ]);
        
        $qrCodeUrl = Settings::getQrCodeUrl();
        
        return view('admin.settings', compact('paymentSections', 'qrCodeUrl'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        // Validate admin profile settings
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'confirm_password' => 'nullable|string',
            'email_notifications' => 'boolean',
            'auto_approve_registrations' => 'boolean',
            'payment_sections' => 'nullable|array',
            'payment_sections.*.name' => 'required|string|max:255',
            'payment_sections.*.number' => 'nullable|string|max:255',
            'payment_sections.*.account_name' => 'nullable|string|max:255',
            'payment_sections.*.enabled' => 'boolean',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Update admin name and email
        $user->name = $request->admin_name;
        $user->email = $request->admin_email;

        // Handle password change if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password saat ini tidak benar'])
                    ->withInput();
            }

            // Update password
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Handle payment sections settings
        if ($request->has('payment_sections')) {
            Settings::set('payment_sections', $request->payment_sections, 'json', 'Payment sections configuration');
        }

        // Handle QR code upload
        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            $oldQrPath = Settings::get('qr_code_path');
            if ($oldQrPath && Storage::disk('public')->exists($oldQrPath)) {
                Storage::disk('public')->delete($oldQrPath);
            }

            // Store new QR code
            $qrPath = $request->file('qr_code')->store('qr-codes', 'public');
            Settings::set('qr_code_path', $qrPath, 'text', 'QR Code image path');
        }
        
        return redirect()->route('admin.settings')
                        ->with('success', 'Pengaturan admin berhasil disimpan!');
    }
    

    
    /**
     * Send email notification to user
     */
    public function sendEmailNotification(Request $request)
    {
        try {
            $request->validate([
                'registration_id' => 'required|exists:event_registrations,id'
            ]);
            
            $registration = EventRegistration::with(['user', 'event'])->findOrFail($request->registration_id);
            
            // Send email based on current status
            \Mail::to($registration->user->email)->send(new \App\Mail\RegistrationStatusUpdate($registration));
            
            \Log::info('Email notification sent successfully', [
                'registration_id' => $registration->id,
                'email' => $registration->user->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . $registration->user->email
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error sending email notification: ' . $e->getMessage(), [
                'registration_id' => $request->registration_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    

}
