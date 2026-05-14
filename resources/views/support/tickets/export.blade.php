<table border="1">
    <thead>
        <tr>
            <th>ID Tiket</th>
            <th>Pelapor</th>
            <th>Email</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Ditangani Oleh</th>
            <th>Tanggal Dibuat</th>
            <th>Tanggal Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>ITC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $ticket->user->name ?? '-' }}</td>
                <td>{{ $ticket->user->email ?? '-' }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->category }}</td>
                <td>{{ $ticket->priority }}</td>
                <td>
                    {{ $ticket->status == 'Open' ? 'Baru Masuk' : ($ticket->status == 'In Progress' ? 'Sedang Ditangani' : 'Selesai Ditangani') }}
                </td>
                <td>{{ $ticket->assignedSupport->name ?? 'Belum ditugaskan' }}</td>
                <td>{{ $ticket->created_at?->format('Y-m-d H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('Y-m-d H:i') }}</td>

<td>
    {{ $ticket->resolved_at 
        ? \Carbon\Carbon::parse($ticket->resolved_at)->format('Y-m-d H:i')
        : '-' }}
</td>
            </tr>
        @endforeach
    </tbody>
</table>