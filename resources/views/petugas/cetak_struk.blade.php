<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Parkir #{{ $transaksi->id_parkir }}</title>
    <style>
        body { font-family: monospace; width: 80mm; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; }
        @media print { @page { margin: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin:0;">PARKIR SYSTEM</h3>
        <p style="margin:2px 0; font-size:12px;">Tiket Masuk Parkir</p>
    </div>
    <div class="line"></div>
    <div style="font-size:13px; line-height:1.5;">
        <div class="flex"><span>No Tiket:</span><strong>#{{ $transaksi->id_parkir }}</strong></div>
        <div class="flex"><span>Plat Nomor:</span><strong>{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</strong></div>
        <div class="flex"><span>Waktu Masuk:</span><span>{{ $transaksi->waktu_masuk }}</span></div>
    </div>
    <div class="line"></div>
    <div class="text-center" style="font-size:11px;">
        <p>Simpan tiket ini untuk keluar parkir.</p>
    </div>
</body>
</html>