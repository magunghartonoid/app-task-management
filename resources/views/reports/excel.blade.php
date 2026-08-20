<table>
    <tr>
        <td colspan="10" style="font-size:16px; font-weight:bold; background-color:#4e73df; color:#ffffff; text-align:center;">
            Report Permintaan Klien
        </td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#dbe4ff;">Periode</td>
        <td colspan="9">
            {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}
            s/d
            {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
        </td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#dbe4ff;">Klien</td>
        <td colspan="9">{{ $client }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#dbe4ff;">Nama Project</td>
        <td colspan="9">{{ $project }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#dbe4ff;">Penanggung Jawab</td>
        <td colspan="9">{{ $pic }}</td>
    </tr>
    <tr>
        <td style="font-weight:bold; background-color:#dbe4ff;">Nama Developer</td>
        <td colspan="9">{{ $developer }}</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <th style="background-color:#4e73df; color:#ffffff;">No</th>
        <th style="background-color:#4e73df; color:#ffffff;">Klien</th>
        <th style="background-color:#4e73df; color:#ffffff;">Dibuat Oleh</th>
        <th style="background-color:#4e73df; color:#ffffff;">Ditugaskan Ke</th>
        <th style="background-color:#4e73df; color:#ffffff;">Request</th>
        <th style="background-color:#4e73df; color:#ffffff;">Tanggal Mulai</th>
        <th style="background-color:#4e73df; color:#ffffff;">Deadline</th>
        <th style="background-color:#4e73df; color:#ffffff;">Priority</th>
        <th style="background-color:#4e73df; color:#ffffff;">Status</th>
        <th style="background-color:#4e73df; color:#ffffff;">Lampiran</th>
    </tr>
    @forelse ($requests as $index => $request)
        <tr>
            <td>{{ $index + 1 }}</td>
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
            <td colspan="10">Tidak ada data request pada periode ini.</td>
        </tr>
    @endforelse
</table>
