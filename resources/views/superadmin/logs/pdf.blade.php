<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Log Gaskeun</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Log Aktivitas Sistem GASKEUN</h2>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Waktu</th>
                <th width="20%">Pengguna (Aktor)</th>
                <th width="25%">Aktivitas</th>
                <th width="30%">Modul / Objek</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->causer ? $log->causer->name : 'Sistem' }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->subject_type ? class_basename($log->subject_type) . ' (ID: ' . $log->subject_id . ')' : '-' }}</td>
            </tr>
            @endforeach
            @if($logs->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data log pada periode yang dipilih.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
