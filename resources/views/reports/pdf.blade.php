<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Permintaan Klien</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        table { width: 100%; border-collapse: collapse; }

        .title-table td { padding: 8px; }
        .title { font-size: 18px; font-weight: bold; background-color: #4e73df; color: #fff; text-align: center; }

        .info-table td { padding: 4px 6px; border: 1px solid #999; }
        .info-label { font-weight: bold; background-color: #dbe4ff; width: 150px; }

        .data-table { margin-top: 15px; }
        .data-table th, .data-table td { border: 1px solid #999; padding: 5px 6px; text-align: left; vertical-align: top; }
        .data-table th { background-color: #4e73df; color: #fff; }
        .data-table tr:nth-child(even) { background-color: #f5f5f5; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <table class="title-table">
        <tr><td class="title">Report Permintaan Klien</td></tr>
    </table>

    <table class="info-table" style="margin-top:10px;">
        <tr>
            <td class="info-label">Periode</td>
            <td>
                {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}
                s/d {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Klien</td>
            <td>{{ $client }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Project</td>
            <td>{{ $project }}</td>
        </tr>
        <tr>
            <td class="info-label">Penanggung Jawab</td>
            <td>{{ $pic }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Developer</td>
            <td>{{ $developer }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width:25px;">No</th>
                <th>Klien</th>
                <th>Dibuat Oleh</th>
                <th>Ditugaskan Ke</th>
                <th>Request</th>
                <th style="width:70px;">Tgl Mulai</th>
                <th style="width:70px;">Deadline</th>
                <th style="width:55px;">Priority</th>
                <th style="width:70px;">Status</th>
                <th style="width:70px;">Lampiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $index => $request)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $request->client->client_name ?? '-' }}</td>
                    <td>{{ $request->createdBy->name ?? '-' }}</td>
                    <td>{{ $request->assignedTo->name ?? '-' }}</td>
                    <td>{{ $request->request }}</td>
                    <td>{{ optional($request->request_start_date)->format('d-m-Y') }}</td>
                    <td>{{ $request->request_deadline_date ? $request->request_deadline_date->format('d-m-Y') : '-' }}</td>
                    <td>{{ ucfirst($request->priority) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $request->status)) }}</td>
                    <td>{{ $request->file ? 'Upload' : 'Tidak Upload' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data request pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
