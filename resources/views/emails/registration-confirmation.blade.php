<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Pendaftaran Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .event-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .registration-data {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .payment-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">🎉</div>
        <h1>Pendaftaran Berhasil!</h1>
        <p>Terima kasih telah mendaftar</p>
    </div>
    
    <div class="content">
        <p>Halo <strong>{{ $userName }}</strong>,</p>
        
        <p>Terima kasih telah mendaftar untuk event berikut:</p>
        
        <div class="event-details">
            <h3>📌 {{ $eventName }}</h3>
            @if($eventDate)
            <p><strong>📅 Tanggal:</strong> {{ \Carbon\Carbon::parse($eventDate)->format('d F Y, H:i') }} WIB</p>
            @endif
            @if($eventLocation)
            <p><strong>📍 Lokasi:</strong> {{ $eventLocation }}</p>
            @endif
            @if($eventPrice > 0)
            <p><strong>💰 Biaya:</strong> Rp {{ number_format($eventPrice, 0, ',', '.') }}</p>
            @else
            <p><strong>💰 Biaya:</strong> <span style="color: #28a745; font-weight: bold;">GRATIS</span></p>
            @endif
            <p><strong>🎫 ID Registrasi:</strong> #{{ $registrationId }}</p>
        </div>
        
        @if($registration->registrationData && $registration->registrationData->count() > 0)
        <h4>📋 Data Pendaftaran Anda:</h4>
        <div class="registration-data">
            @foreach($registration->registrationData as $data)
                @if($data->eventFormField && $data->field_value)
                <p><strong>{{ $data->eventFormField->field_label }}:</strong> {{ $data->field_value }}</p>
                @endif
            @endforeach
        </div>
        @endif
        
        @if($eventPrice > 0)
        <div class="payment-info">
            <h4>💳 Informasi Pembayaran</h4>
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($eventPrice, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> <span style="color: #856404; font-weight: bold;">Menunggu Verifikasi Pembayaran</span></p>
            
            <h5>📋 Instruksi Pembayaran:</h5>
            <ol>
                <li>Transfer ke: <strong>BCA 1234567890 (Event Manager)</strong></li>
                <li>Nominal: <strong>Rp {{ number_format($eventPrice, 0, ',', '.') }}</strong></li>
                <li>Upload bukti pembayaran di website</li>
                <li>Tunggu verifikasi dari admin</li>
            </ol>
            
            <p><small>⚠️ <strong>Penting:</strong> Pendaftaran Anda akan dikonfirmasi setelah pembayaran diverifikasi oleh admin.</small></p>
        </div>
        @else
        <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="color: #155724; margin: 0;">✅ <strong>Event ini gratis!</strong> Pendaftaran Anda sudah berhasil dan menunggu konfirmasi admin.</p>
        </div>
        @endif
        
        <h4>📝 Langkah Selanjutnya:</h4>
        <ul>
            <li>✅ Simpan email ini sebagai bukti pendaftaran</li>
            @if($eventPrice > 0)
            <li>💳 Lakukan pembayaran sesuai instruksi di atas</li>
            <li>📤 Upload bukti pembayaran di website</li>
            @endif
            <li>⏰ Tunggu konfirmasi dari admin</li>
            <li>📱 Pantau email untuk update status pendaftaran</li>
        </ul>
        
        <p>Jika ada pertanyaan, jangan ragu untuk menghubungi tim support kami.</p>
        
        <div class="footer">
            <p>🙏 <strong>Terima kasih,</strong><br>
            <strong>Tim Event Manager</strong> 💙</p>
            
            <p><small>Email ini dikirim secara otomatis. Jika ada pertanyaan, silakan hubungi tim support kami.</small></p>
        </div>
    </div>
</body>
</html>