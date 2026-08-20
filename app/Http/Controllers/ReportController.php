<?php

namespace App\Http\Controllers;

use App\Exports\RequestsExport;
use App\Models\Client;
use App\Models\Request as RequestModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request as HttpRequest;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman form filter report.
     */
    public function index()
    {
        $clients = Client::orderBy('client_name')->get();
        $statuses = $this->statusOptions();

        return view('reports.index', compact('clients', 'statuses'));
    }

    /**
     * Proses filter & generate report (excel/pdf).
     */
    public function generate(HttpRequest $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'client_id'  => 'nullable|exists:clients,id',
            'status'     => 'nullable|in:pending,in_progress,testing,completed,canceled',
            'format'     => 'required|in:excel,pdf',
        ]);

        $requests = $this->filteredRequests($validated);
        $client = !empty($validated['client_id']) ? Client::find($validated['client_id']) : null;

        $header = [
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'client'     => $client->client_name ?? 'Semua Klien',
            'project'    => $client->project_name ?? '-',
            'pic'        => $client->client_poc ?? '-',
            'developer'  => $client->project_developer ?? '-',
        ];

        if ($validated['format'] === 'excel') {
            $fileName = 'report-request-' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new RequestsExport($requests, $header), $fileName);
        }

        $fileName = 'report-request-' . now()->format('Ymd_His') . '.pdf';
        $pdf = Pdf::loadView('reports.pdf', array_merge($header, ['requests' => $requests]))
            ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    /**
     * Ambil data request sesuai filter (dipakai untuk excel & pdf).
     */
    private function filteredRequests(array $filters)
    {
        return RequestModel::with(['client', 'createdBy', 'assignedTo'])
            ->whereDate('request_start_date', '>=', $filters['start_date'])
            ->whereDate('request_start_date', '<=', $filters['end_date'])
            ->when(!empty($filters['client_id']), function ($query) use ($filters) {
                $query->where('client_id', $filters['client_id']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderBy('request_start_date')
            ->get();
    }

    /**
     * Daftar status request (samakan dengan enum di migration requests).
     */
    private function statusOptions(): array
    {
        return [
            'pending'     => 'Pending',
            'in_progress' => 'In Progress',
            'testing'     => 'Testing',
            'completed'   => 'Completed',
            'canceled'    => 'Canceled',
        ];
    }
}
