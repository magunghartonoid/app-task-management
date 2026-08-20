@extends('sb2admin.layouts.app')

@section('title', 'Manajement Request')

@push('styles')
    {{-- DataTables - styling Bootstrap 5 - CDNJS --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.css">
    {{-- SweetAlert2 - CDNJS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">
@endpush

@section('content')

    <h1 class="mt-4">Manajemen Request</h1>
    <ol class="breadcrumb ab-4">
        <li class="breadcrumb-item active">Requests</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> Daftar Request</span>
            <a href="{{ route('requests.create') }}"class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Request
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="tableRequests" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Klien</th>
                        <th style="width:180px;">Created By</th>
                        <th style="width:180px;">Assigned To</th>
                        <th>Request</th>
                        <th style="width:230px;">Tanggal Mulai</th>
                        <th style="width:150px;">Deadline</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- jQuery (dibutuhkan DataTables) - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- DataTables core(WAJIB dimuat sebelum addon styling) - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/2.2.2/dataTables.min.js"></script>
    {{-- DataTables addon styling Bootstrap 5 - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.js"></script>
    {{-- SweetAlert2-CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    <script>
        $(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': csrfToken } });

    const routes = {
        data: "{{ route('requests.data') }}",
        destroy: (id) => "{{ url('requests') }}/" + id,
    };

    // ============ DATATABLE ============
    const table = $('#tableRequests').DataTable({
        retrieve: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: routes.data,
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'client_name', name: 'client.cilent_name' },
            { data: 'created_by_name', name: 'createdBy.name' },
            { data: 'assigned_to_name', name: 'assignedTo.name' },
            { data: 'request', name: 'request' },
            { data: 'request_start_date', name: 'request_start_date' },
            { data: 'request_deadline_date', name: 'request_deadline_date'},
            { data: 'priority', name: 'priority' },
            { data: 'status', name: 'status' },
            { data: 'lampiran', name: 'lampiran', orderable: false, searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ],
        language: {
            processing: "Memuat data...",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data",
            search: "Cari:",
            paginate: {previous: "Sebelumnya", next: "Berikutnya"},
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)"
        }
    });

    // ============ HELPER ============
    function setButtonLoading(btn, loading) {
        const $btn = $(btn);
        if (loading) {
            $btn.data('original-html', $btn.html());
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        } else {
            $btn.prop('disabled', false);
            $btn.html($btn.data('original-html'));
        }
    }

    // ============ DELETE REQUEST ============
    $('#tableRequests').on('click', '.btn-delete-request', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const $btn = $(this);

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: 'Request <b>' + name + '</b> akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
        }).then(function (result) {
            if (result.isConfirmed) {
                setButtonLoading($btn, true);

                $.ajax({
                    url: routes.destroy(id),
                    type: 'POST',
                    data: { _method: 'DELETE'},
                }).done(function (res) {
                    table.ajax.reload(null, false);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });

                }).fail(function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Gagal menghapus request.';
                    Swal.fire('Gagal', msg, 'error');
                    setButtonLoading($btn, false);

                });

            }

        });

    });
});
</script>
@endpush