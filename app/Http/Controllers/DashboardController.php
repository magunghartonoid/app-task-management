<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private array $statusKeys = ['pending', 'in_progress', 'testing', 'completed', 'canceled'];

    private array $priorityKeys = ['low', 'medium', 'high', 'urgent'];

    public function index()
    {
        return view('dashboard', array_merge(
            $this->kpiData(),
            [
                'statusChart'   => $this->statusChartData(),
                'priorityChart' => $this->priorityChartData(),
                'trendChart'    => $this->trendChartData(),
                'workloadChart' => $this->workloadChartData(),
                'deadlineRequests' => $this->deadlineRequests(),
                'recentRequests'   => $this->recentRequests(),
                'clients'           => Client::orderBy('client_name')->get(['id', 'client_name']),
            ]
        ));
    }

    /**
     * AJAX: daftar request belum completed milik satu klien (dipakai oleh dropdown di dashboard).
     */
    public function clientIncompleteRequests(Client $client)
    {
        $requests = RequestModel::where('client_id', $client->id)
            ->whereNotIn('status', ['completed', 'canceled'])
            ->orderBy('request_deadline_date')
            ->get(['id', 'request', 'request_start_date', 'request_deadline_date', 'status', 'priority']);

        return response()->json([
            'success'  => true,
            'requests' => $requests->map(function ($request) {
                return [
                    'id'                     => $request->id,
                    'request'                => $request->request,
                    'request_start_date'     => optional($request->request_start_date)->format('d-m-Y'),
                    'request_deadline_date'  => $request->request_deadline_date ? $request->request_deadline_date->format('d-m-Y') : '-',
                    'is_overdue'             => $request->request_deadline_date && $request->request_deadline_date->isPast(),
                    'status'                 => $request->status,
                    'priority'               => $request->priority,
                ];
            }),
        ]);
    }

    /**
     * Angka ringkasan (KPI cards).
     */
    private function kpiData(): array
    {
        return [
            'totalRequests' => RequestModel::count(),
            'totalClients'  => Client::count(),
            'activeProjects' => Client::where(function ($query) {
                $query->whereNull('project_end_date')
                    ->orWhereDate('project_end_date', '>=', now());
            })->count(),
            'overdueRequests' => RequestModel::whereDate('request_deadline_date', '<', now())
                ->whereNotIn('status', ['completed', 'canceled'])
                ->count(),
        ];
    }

    /**
     * Data untuk pie chart distribusi status.
     */
    private function statusChartData(): array
    {
        $counts = RequestModel::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [
            'pending'     => 'Menunggu',
            'in_progress' => 'Diproses',
            'testing'     => 'Pengujian',
            'completed'   => 'Selesai',
            'canceled'    => 'Dibatalkan',
        ];

        $colors = [
            'pending'     => '#6c757d',
            'in_progress' => '#0d6efd',
            'testing'     => '#ffc107',
            'completed'   => '#198754',
            'canceled'    => '#dc3545',
        ];

        return [
            'labels' => array_values($labels),
            'data'   => array_map(fn ($key) => $counts[$key] ?? 0, $this->statusKeys),
            'colors' => array_values($colors),
        ];
    }

    /**
     * Data untuk bar chart distribusi priority.
     */
    private function priorityChartData(): array
    {
        $counts = RequestModel::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $labels = [
            'low'    => 'Rendah',
            'medium' => 'Sedang',
            'high'   => 'Tinggi',
            'urgent' => 'Mendesak',
        ];

        $colors = [
            'low'    => '#0dcaf0',
            'medium' => '#ffc107',
            'high'   => '#fd7e14',
            'urgent' => '#dc3545',
        ];

        return [
            'labels' => array_values($labels),
            'data'   => array_map(fn ($key) => $counts[$key] ?? 0, $this->priorityKeys),
            'colors' => array_values($colors),
        ];
    }

    /**
     * Data untuk line chart tren jumlah request per bulan (12 bulan terakhir).
     * Dikelompokkan di PHP (bukan raw SQL) supaya kompatibel di semua driver DB (mysql, sqlite, dll).
     */
    private function trendChartData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $counts = RequestModel::where('request_start_date', '>=', $start)
            ->get(['request_start_date'])
            ->groupBy(fn ($request) => $request->request_start_date->format('Y-m'))
            ->map->count();

        $labels = [];
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
            $data[] = $counts[$key] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Data untuk stacked bar chart beban kerja per developer (assigned_to), dipecah per status.
     */
    private function workloadChartData(): array
    {
        $raw = RequestModel::select('assigned_to', 'status', DB::raw('count(*) as total'))
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to', 'status')
            ->get();

        $developerIds = $raw->pluck('assigned_to')->unique()->values();
        $developers = User::whereIn('id', $developerIds)->pluck('name', 'id');

        $matrix = [];
        foreach ($raw as $row) {
            $matrix[$row->assigned_to][$row->status] = $row->total;
        }

        $statusLabels = [
            'pending'     => 'Menunggu',
            'in_progress' => 'Diproses',
            'testing'     => 'Pengujian',
            'completed'   => 'Selesai',
            'canceled'    => 'Dibatalkan',
        ];

        $statusColors = [
            'pending'     => '#6c757d',
            'in_progress' => '#0d6efd',
            'testing'     => '#ffc107',
            'completed'   => '#198754',
            'canceled'    => '#dc3545',
        ];

        $datasets = [];
        foreach ($this->statusKeys as $status) {
            $datasets[] = [
                'label'           => $statusLabels[$status],
                'backgroundColor' => $statusColors[$status],
                'data'            => $developers->keys()->map(fn ($id) => $matrix[$id][$status] ?? 0)->values(),
            ];
        }

        return [
            'labels'   => $developers->values(),
            'datasets' => $datasets,
        ];
    }

    /**
     * Tabel: request yang mendekati/lewat deadline (belum completed/canceled), diurutkan paling mepet dulu.
     */
    private function deadlineRequests()
    {
        return RequestModel::with(['client', 'assignedTo'])
            ->whereNotNull('request_deadline_date')
            ->whereNotIn('status', ['completed', 'canceled'])
            ->orderBy('request_deadline_date')
            ->take(10)
            ->get();
    }

    /**
     * Tabel: request terbaru yang dibuat.
     */
    private function recentRequests()
    {
        return RequestModel::with(['client', 'createdBy'])
            ->latest()
            ->take(8)
            ->get();
    }
}
