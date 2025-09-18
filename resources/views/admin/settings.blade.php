@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
<div class="settings-management">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-cog"></i>
            Pengaturan Sistem
        </h1>
        <p class="page-description">Kelola pengaturan aplikasi dan konfigurasi sistem</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="settings-container">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="settings-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Admin Profile Settings -->
            <div class="settings-section">
                <h3 class="section-title">
                    <i class="fas fa-user-cog"></i>
                    Pengaturan Profil Admin
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="admin_name">Nama Admin</label>
                        <input type="text" id="admin_name" name="admin_name" 
                               value="{{ old('admin_name', auth()->user()->name ?? 'Administrator') }}" 
                               class="form-control" required>
                        @error('admin_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admin_email">Email Admin</label>
                        <input type="email" id="admin_email" name="admin_email" 
                               value="{{ old('admin_email', auth()->user()->email ?? 'admin@eventmanager.com') }}" 
                               class="form-control" required>
                        @error('admin_email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="settings-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i>
                    Ganti Password
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" 
                               class="form-control" placeholder="Masukkan password saat ini">
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" 
                               class="form-control" placeholder="Masukkan password baru">
                        @error('new_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               class="form-control" placeholder="Konfirmasi password baru">
                        @error('confirm_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Sections Settings -->
            <div class="settings-section">
                <h3 class="section-title">
                    <i class="fas fa-credit-card"></i>
                    Pengaturan Bagian Pembayaran
                </h3>
                
                <div id="payment-sections">
                    @foreach($paymentSections as $index => $section)
                    <div class="payment-section-item" data-index="{{ $index }}">
                        <div class="section-header">
                            <h4>Bagian {{ $index + 1 }}</h4>
                            <button type="button" class="btn-remove-section" onclick="removePaymentSection({{ $index }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="payment_sections_{{ $index }}_name">Nama Bagian</label>
                                <input type="text" id="payment_sections_{{ $index }}_name" 
                                       name="payment_sections[{{ $index }}][name]" 
                                       value="{{ old('payment_sections.'.$index.'.name', $section['name']) }}" 
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="payment_sections_{{ $index }}_number">Nomor/Rekening</label>
                                <input type="text" id="payment_sections_{{ $index }}_number" 
                                       name="payment_sections[{{ $index }}][number]" 
                                       value="{{ old('payment_sections.'.$index.'.number', $section['number']) }}" 
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="payment_sections_{{ $index }}_account_name">Nama Akun</label>
                                <input type="text" id="payment_sections_{{ $index }}_account_name" 
                                       name="payment_sections[{{ $index }}][account_name]" 
                                       value="{{ old('payment_sections.'.$index.'.account_name', $section['account_name']) }}" 
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="payment_sections[{{ $index }}][enabled]" value="1" 
                                               {{ old('payment_sections.'.$index.'.enabled', $section['enabled']) ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        Aktifkan Bagian Ini
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" class="btn btn-secondary" onclick="addPaymentSection()">
                    <i class="fas fa-plus"></i>
                    Tambah Bagian Pembayaran
                </button>
            </div>

            <!-- QR Code Settings -->
            <div class="settings-section">
                <h3 class="section-title">
                    <i class="fas fa-qrcode"></i>
                    Pengaturan QR Code
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="qr_code">Upload QR Code</label>
                        <input type="file" id="qr_code" name="qr_code" 
                               class="form-control" accept="image/*">
                        <small class="help-text">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.</small>
                        @error('qr_code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    @if($qrCodeUrl)
                    <div class="form-group">
                        <label>QR Code Saat Ini</label>
                        <div class="qr-preview">
                            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="qr-image">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

         

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.settings-management {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-title {
    font-family: 'Orbitron', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    background: var(--gradient-accent);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.page-title i {
    margin-right: 1rem;
    color: var(--cyan);
}

.page-description {
    font-size: 1.1rem;
    color: var(--text-gray);
    margin: 0;
}

.alert {
    background: var(--glass-bg);
    color: var(--text-light);
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid rgba(34, 197, 94, 0.3);
    backdrop-filter: blur(15px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

.alert i {
    margin-right: 0.5rem;
    color: #22c55e;
}

.settings-container {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.settings-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.settings-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 0.75rem;
    color: var(--bright-blue);
    font-size: 1.25rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control {
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #1f2937;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--bright-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    background: rgba(255, 255, 255, 0.1);
}

.form-control::placeholder {
    color: #9ca3af;
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 500;
    color: #1f2937;
    position: relative;
    padding-left: 2rem;
}

.checkbox-label input[type="checkbox"] {
    position: absolute;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 20px;
    width: 20px;
    background-color: #ffffff;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.checkbox-label input:checked ~ .checkmark {
    background-color: var(--bright-blue);
    border-color: var(--bright-blue);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-label input:checked ~ .checkmark:after {
    display: block;
}

/* Payment Sections Styles */
.payment-section-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    position: relative;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.section-header h4 {
    color: #1f2937;
    margin: 0;
    font-size: 1.1rem;
}

.btn-remove-section {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove-section:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
}

/* QR Code Styles */
.qr-preview {
    display: flex;
    justify-content: center;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.qr-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.help-text {
    color: #6b7280;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--gradient-accent);
    color: white;
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: var(--glass-bg);
    color: var(--text-light);
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
    color: var(--text-light);
    text-decoration: none;
}

@media (max-width: 768px) {
    .settings-management {
        padding: 1rem;
    }
    
    .settings-container {
        padding: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .page-title {
        font-size: 2rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
let paymentSectionIndex = {{ count($paymentSections) }};

function addPaymentSection() {
    const container = document.getElementById('payment-sections');
    const newSection = document.createElement('div');
    newSection.className = 'payment-section-item';
    newSection.setAttribute('data-index', paymentSectionIndex);
    
    newSection.innerHTML = `
        <div class="section-header">
            <h4>Bagian ${paymentSectionIndex + 1}</h4>
            <button type="button" class="btn-remove-section" onclick="removePaymentSection(${paymentSectionIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="payment_sections_${paymentSectionIndex}_name">Nama Bagian</label>
                <input type="text" id="payment_sections_${paymentSectionIndex}_name" 
                       name="payment_sections[${paymentSectionIndex}][name]" 
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_sections_${paymentSectionIndex}_number">Nomor/Rekening</label>
                <input type="text" id="payment_sections_${paymentSectionIndex}_number" 
                       name="payment_sections[${paymentSectionIndex}][number]" 
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="payment_sections_${paymentSectionIndex}_account_name">Nama Akun</label>
                <input type="text" id="payment_sections_${paymentSectionIndex}_account_name" 
                       name="payment_sections[${paymentSectionIndex}][account_name]" 
                       class="form-control">
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="payment_sections[${paymentSectionIndex}][enabled]" value="1" checked>
                        <span class="checkmark"></span>
                        Aktifkan Bagian Ini
                    </label>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newSection);
    paymentSectionIndex++;
    
    // Add animation
    newSection.style.opacity = '0';
    newSection.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        newSection.style.transition = 'all 0.3s ease';
        newSection.style.opacity = '1';
        newSection.style.transform = 'translateY(0)';
    }, 10);
}

function removePaymentSection(index) {
    const section = document.querySelector(`[data-index="${index}"]`);
    if (section) {
        section.style.transition = 'all 0.3s ease';
        section.style.opacity = '0';
        section.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            section.remove();
            updateSectionNumbers();
        }, 300);
    }
}

function updateSectionNumbers() {
    const sections = document.querySelectorAll('.payment-section-item');
    sections.forEach((section, index) => {
        const header = section.querySelector('.section-header h4');
        if (header) {
            header.textContent = `Bagian ${index + 1}`;
        }
    });
}

// File input preview
document.getElementById('qr_code').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview if doesn't exist
            let preview = document.querySelector('.qr-preview');
            if (!preview) {
                const previewContainer = document.createElement('div');
                previewContainer.className = 'form-group';
                previewContainer.innerHTML = `
                    <label>Preview QR Code</label>
                    <div class="qr-preview">
                        <img src="" alt="QR Code Preview" class="qr-image">
                    </div>
                `;
                e.target.closest('.form-group').after(previewContainer);
                preview = previewContainer.querySelector('.qr-preview');
            }
            
            const img = preview.querySelector('img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection