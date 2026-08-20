@extends('sb2admin.layouts.app')

@section('title', 'Report Request')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
@endpush

@section('content')

    <h1 class="mt-4">Report Request</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Report</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-export me-1"></i> Filter Report
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reports.generate') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Klien</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">Semua Klien</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status Request</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Format Report</label>
                        <select name="format" id="format" class="form-select" required>
                            <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel (.xlsx)</option>
                            <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#client_id').select2({
                placeholder: '-- Pilih Klien --',
                width: '100%'
            });

            $('#status').select2({
                placeholder: '-- Pilih Status --',
                width: '100%'
            });

            $('#format').select2({
                placeholder: '-- Pilih Format --',
                width: '100%',
                minimumResultsForSearch: -1
            });
        });
    </script>
@endpush
