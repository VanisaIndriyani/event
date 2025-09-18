<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Event Disetujui</title>
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
            border-left: 4px solid #28a745;
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
            background: #28a745;
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
        <h1>Selamat!</h1>
        <p>Pendaftaran Event Anda Telah Disetujui</p>
    </div>
    
    <div class="content">
        <p>Halo <strong>{{ $userName }}</strong>,</p>
        
        <p>Kami dengan senang hati mengonfirmasi bahwa pendaftaran Anda untuk event berikut telah <strong>disetujui</strong>:</p>
        
        <div class="event-details">
            <h3>📌 {{ $eventName }}</h3>
            @if($eventDate)
            <p><strong>📅 Tanggal:</strong> {{ \Carbon\Carbon::parse($eventDate)->format('d F Y, H:i') }} WIB</p>
            @endif
            @if($eventLocation)
            <p><strong>📍 Lokasi:</strong> {{ $eventLocation }}</p>
            @endif
            <p><strong>🎫 Status:</strong> <span style="color: #28a745; font-weight: bold;">DIKONFIRMASI</span></p>
        </div>
        
        <h4>🎯 Langkah Selanjutnya:</h4>
        <ul>
            <li>✅ Simpan email ini sebagai bukti konfirmasi</li>
            <li>⏰ Hadir tepat waktu sesuai jadwal</li>
            <li>🆔 Bawa identitas diri yang valid</li>
            <li>📱 Pantau update terbaru melalui email atau website</li>
        </ul>
        
        <p>Terima kasih atas partisipasi Anda! Kami sangat menantikan kehadiran Anda di event yang luar biasa ini! 🚀</p>
        
        <div class="footer">
            <p>💫 <strong>Salam Hangat,</strong><br>
            <strong>Tim Event Manager</strong> 💙</p>
            
            <p><small>Email ini dikirim secara otomatis. Jika ada pertanyaan, silakan hubungi tim support kami.</small></p>
        </div>
    </div>
</body>
</html>