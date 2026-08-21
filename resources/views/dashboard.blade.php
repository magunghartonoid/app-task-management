@extends('sb2admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">
    <style>
        .kpi-card { border-left: 4px solid #4e73df; }
        .kpi-card .kpi-value { font-size: 1.8rem; font-weight: 700; }
        .kpi-card.overdue { border-left-color: #dc3545; }
        .kpi-card.completed { border-left-color: #198754; }
        .badge-overdue { background-color: #dc3545; color: #fff; }
    </style>
@endpush

@section('content')

    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    {{-- ===================== KPI CARDS ===================== --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total Permintaan</div>
                    <div class="kpi-value">{{ $totalRequests }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total Klien</div>
                    <div class="kpi-value">{{ $totalClients }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card completed shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Proyek Aktif</div>
                    <div class="kpi-value">{{ $activeProjects }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card kpi-card overdue shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Permintaan Melewati Tenggat</div>
                    <div class="kpi-value text-danger">{{ $overdueRequests }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== CHARTS ===================== --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Distribusi Status Permintaan</div>
                <div class="card-body">
                    <canvas id="chartStatus" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Distribusi Prioritas Permintaan</div>
                <div class="card-body">
                    <canvas id="chartPriority" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Tren Permintaan (12 Bulan Terakhir)</div>
                <div class="card-body">
                    <canvas id="chartTrend" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Beban Kerja per Pengembang</div>
                <div class="card-body">
                    <canvas id="chartWorkload" height="90"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== REQUEST BELUM COMPLETE PER KLIEN ===================== --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-list-check me-1"></i> Permintaan Belum Selesai per Klien
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="filterClient" class="form-select">
                                <option value="">-- Pilih Klien --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="clientRequestsEmpty" class="text-muted">Pilih klien dulu untuk melihat permintaan yang belum selesai.</div>

                    <div class="table-responsive" id="clientRequestsWrapper" style="display:none;">
                        <table class="table table-bordered table-hover align-middle" id="tableClientRequests" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:30px;">No</th>
                                    <th>Permintaan</th>
                                    <th style="width:110px;">Tanggal Mulai</th>
                                    <th style="width:110px;">Tenggat Waktu</th>
                                    <th style="width:100px;">Prioritas</th>
                                    <th style="width:110px;">Status</th>
                                    <th style="width:110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="clientRequestsBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TABEL DEADLINE & RECENT ===================== --}}
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Permintaan Mendekati / Melewati Tenggat</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered" id="tableDeadlineRequests" style="width:100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Permintaan</th>
                                <th>Klien</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deadlineRequests as $request)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($request->request, 40) }}</td>
                                    <td>{{ $request->client->client_name ?? '-' }}</td>
                                    <td>
                                        {{ $request->request_deadline_date ? $request->request_deadline_date->format('d-m-Y') : '-' }}
                                        @if ($request->request_deadline_date && $request->request_deadline_date->isPast())
                                            <span class="badge badge-overdue">Terlambat</span>
                                        @endif
                                    </td>
                                    <td><x-status-badge :status="$request->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Permintaan Terbaru</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered" id="tableRecentRequests" style="width:100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Permintaan</th>
                                <th>Klien</th>
                                <th>Dibuat Oleh</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentRequests as $request)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($request->request, 40) }}</td>
                                    <td>{{ $request->client->client_name ?? '-' }}</td>
                                    <td>{{ $request->createdBy->name ?? '-' }}</td>
                                    <td><x-status-badge :status="$request->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/2.2.2/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

        $(function () {
            $('#filterClient').select2({
                placeholder: '-- Pilih Klien --',
                width: '100%'
            });

            $('#tableDeadlineRequests').DataTable({
                pageLength: 5,
                lengthChange: false,
                language: { search: 'Cari:', info: 'Menampilkan _START_-_END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data', zeroRecords: 'Tidak ada data ditemukan', emptyTable: 'Tidak ada data.' }
            });

            $('#tableRecentRequests').DataTable({
                pageLength: 5,
                lengthChange: false,
                language: { search: 'Cari:', info: 'Menampilkan _START_-_END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data', zeroRecords: 'Tidak ada data ditemukan', emptyTable: 'Tidak ada data.' }
            });

            // ============ CHART: STATUS ============
            new Chart(document.getElementById('chartStatus'), {
                type: 'doughnut',
                data: {
                    labels: @json($statusChart['labels']),
                    datasets: [{
                        data: @json($statusChart['data']),
                        backgroundColor: @json($statusChart['colors']),
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            // ============ CHART: PRIORITY ============
            new Chart(document.getElementById('chartPriority'), {
                type: 'bar',
                data: {
                    labels: @json($priorityChart['labels']),
                    datasets: [{
                        label: 'Jumlah Request',
                        data: @json($priorityChart['data']),
                        backgroundColor: @json($priorityChart['colors']),
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // ============ CHART: TREND ============
            new Chart(document.getElementById('chartTrend'), {
                type: 'line',
                data: {
                    labels: @json($trendChart['labels']),
                    datasets: [{
                        label: 'Jumlah Request',
                        data: @json($trendChart['data']),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.15)',
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            // ============ CHART: WORKLOAD (stacked) ============
            new Chart(document.getElementById('chartWorkload'), {
                type: 'bar',
                data: {
                    labels: @json($workloadChart['labels']),
                    datasets: @json($workloadChart['datasets']),
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            // ============ REQUEST BELUM COMPLETE PER KLIEN ============
            const baseUrl = "{{ url('/dashboard/clients') }}";

            const statusBadgeMap = {
                pending:     { label: 'Menunggu', class: 'bg-secondary' },
                in_progress: { label: 'Diproses', style: 'background-color:#0d6efd;color:#fff;' },
                testing:     { label: 'Pengujian', class: 'bg-warning' },
                completed:   { label: 'Selesai', class: 'bg-success' },
                canceled:    { label: 'Dibatalkan', class: 'bg-danger' },
            };

            const priorityBadgeMap = {
                urgent: { label: 'Mendesak', class: 'bg-danger' },
                high:   { label: 'Tinggi', style: 'background-color:#fd7e14;color:#fff;' },
                medium: { label: 'Sedang', class: 'bg-warning' },
                low:    { label: 'Rendah', class: 'bg-info' },
            };

            function renderBadge(map, key) {
                const item = map[key] || { label: key, class: 'bg-secondary' };
                const style = item.style ? ` style="${item.style}"` : '';
                return `<span class="badge ${item.class || ''}"${style}>${item.label}</span>`;
            }

            let clientRequestsTable = null;

            function renderClientRequests(requests) {
                const $body = $('#clientRequestsBody');
                $body.empty();

                if (clientRequestsTable) {
                    clientRequestsTable.destroy();
                    clientRequestsTable = null;
                }

                if (!requests.length) {
                    $body.append('<tr><td colspan="7" class="text-center text-muted">Semua permintaan klien ini sudah selesai.</td></tr>');
                    return;
                }

                requests.forEach(function (req, index) {
                    const deadlineBadge = req.is_overdue
                        ? ' <span class="badge badge-overdue">Terlambat</span>'
                        : '';

                    const statusBadge = renderBadge(statusBadgeMap, req.status);
                    const priorityBadge = renderBadge(priorityBadgeMap, req.priority);

                    const row = `
                        <tr data-row-id="${req.id}">
                            <td>${index + 1}</td>
                            <td>${$('<div>').text(req.request).html()}</td>
                            <td>${req.request_start_date}</td>
                            <td>${req.request_deadline_date}${deadlineBadge}</td>
                            <td>${priorityBadge}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success btn-complete-request" data-id="${req.id}">
                                    <i class="fas fa-check"></i> Selesai
                                </button>
                            </td>
                        </tr>`;
                    $body.append(row);
                });

                clientRequestsTable = $('#tableClientRequests').DataTable({
                    pageLength: 5,
                    lengthChange: false,
                    language: { search: 'Cari:', info: 'Menampilkan _START_-_END_ dari _TOTAL_ data', infoEmpty: 'Tidak ada data', zeroRecords: 'Tidak ada data ditemukan', emptyTable: 'Tidak ada data.' }
                });
            }

            $('#filterClient').on('change', function () {
                const clientId = $(this).val();

                if (!clientId) {
                    $('#clientRequestsWrapper').hide();
                    $('#clientRequestsEmpty').show();
                    return;
                }

                $.get(`${baseUrl}/${clientId}/incomplete-requests`).done(function (res) {
                    $('#clientRequestsEmpty').hide();
                    $('#clientRequestsWrapper').show();
                    renderClientRequests(res.requests);
                }).fail(function () {
                    Swal.fire('Gagal', 'Tidak bisa memuat data permintaan klien ini.', 'error');
                });
            });

            $('#clientRequestsBody').on('click', '.btn-complete-request', function () {
                const id = $(this).data('id');
                const $btn = $(this);

                Swal.fire({
                    title: 'Tandai selesai?',
                    text: 'Permintaan ini akan ditandai selesai.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tandai selesai',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754',
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                    $.ajax({
                        url: `{{ url('/requests') }}/${id}/complete`,
                        type: 'PATCH',
                    }).done(function (res) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        $('#filterClient').trigger('change');
                    }).fail(function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal menandai permintaan sebagai selesai.';
                        Swal.fire('Gagal', msg, 'error');
                        $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Selesai');
                    });
                });
            });
        });
    </script>
@endpush
