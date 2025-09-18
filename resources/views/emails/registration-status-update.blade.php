<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Status Pendaftaran Event</title>
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
        .status-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .event-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status-confirmed {
            border-left: 4px solid #28a745;
        }
        .status-pending {
            border-left: 4px solid #ffc107;
        }
        .status-cancelled, .status-rejected {
            border-left: 4px solid #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .badge-confirmed {
            background: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge-cancelled, .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="status-icon">
            @if($status == 'confirmed')
                🎉
            @elseif($status == 'pending')
                ⏳
            @elseif($status == 'cancelled' || $status == 'rejected')
                ❌
            @else
                📢
            @endif
        </div>
        <h1>Update Status Pendaftaran</h1>
        <p>{{ $eventName }}</p>
    </div>
    
    <div class="content">
        <p>Halo <strong>{{ $userName }}</strong>,</p>
        
        <p>Ada update terkait status pendaftaran Anda untuk event berikut:</p>
        
        <div class="event-details status-{{ $status }}">
            <h3>📌 {{ $eventName }}</h3>
            @if($eventDate)
            <p><strong>📅 Tanggal:</strong> {{ \Carbon\Carbon::parse($eventDate)->format('d F Y, H:i') }} WIB</p>
            @endif
            @if($eventLocation)
            <p><strong>📍 Lokasi:</strong> {{ $eventLocation }}</p>
            @endif
            <p><strong>🎫 Status:</strong> 
                <span class="status-badge badge-{{ $status }}">{{ strtoupper($statusText) }}</span>
            </p>
        </div>
        
        @if($status == 'confirmed')
            <h4>🎯 Langkah Selanjutnya:</h4>
            <ul>
                <li>✅ Simpan email ini sebagai bukti konfirmasi</li>
                <li>⏰ Hadir tepat waktu sesuai jadwal</li>
                <li>🆔 Bawa identitas diri yang valid</li>
                <li>📱 Pantau update terbaru melalui email atau website</li>
            </ul>
            <p>Terima kasih atas partisipasi Anda! Kami sangat menantikan kehadiran Anda di event yang luar biasa ini! 🚀</p>
        @elseif($status == 'pending')
            <p>⏳ Tim kami sedang melakukan review terhadap pendaftaran Anda. Proses ini biasanya memakan waktu 1-2 hari kerja.</p>
            <p>📱 Anda akan mendapat notifikasi segera setelah pendaftaran disetujui.</p>
            <p>✨ <strong>Tetap semangat dan nantikan kabar baik dari kami!</strong></p>
        @elseif($status == 'cancelled' || $status == 'rejected')
            <p>😔 Dengan berat hati kami informasikan bahwa pendaftaran Anda untuk event ini telah {{ $status == 'cancelled' ? 'dibatalkan' : 'ditolak' }}.</p>
            <p>🤝 <strong>Jangan khawatir!</strong> Masih banyak event menarik lainnya yang bisa Anda ikuti.</p>
            <p>📞 Jika ada pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi tim kami.</p>
            <p>💪 <strong>Tetap semangat dan sampai jumpa di event berikutnya!</strong></p>
        @else
            <p>📋 Tim kami akan segera memproses dan memberikan update status pendaftaran Anda.</p>
            <p>💬 Ada pertanyaan? Jangan ragu untuk menghubungi kami kapan saja!</p>
            <p>🚀 <strong>Bersiaplah untuk pengalaman yang tak terlupakan!</strong></p>
        @endif
        
        <div class="footer">
            <p>💫 <strong>Salam Hangat,</strong><br>
            <strong>Tim Event Manager</strong> 💙</p>
            
            <p><small>Email ini dikirim secara otomatis. Jika ada pertanyaan, silakan hubungi tim support kami.</small></p>
        </div>
    </div>
</body>
</html>