@extends('sb2admin.layouts.app')
@section('title', 'Tambah Klien')
@push('styles')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet">
@endpush

@section('content')
<h1 class="mt-4">Tambah Klien</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">
        <a href="{{ route('clients.index') }}">Klien</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
</ol>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-building me-1"></i>
        Form Input Data Klien
    </div>

    <div class="card-body">
        <form id="formClient">
            @csrf

            <div class="row">

                <!-- DATA CLIENT -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-user-tie"></i>
                        Data Klien
                    </h5>

                    <!-- Nama Klien -->
                    <div class="mb-3">
                        <label for="client_name" class="form-label">
                            Nama Klien
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="client_name"
                            name="client_name">

                        <div class="invalid-feedback"
                            data-error="client_name">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="client_address" class="form-label">
                            Alamat Klien
                            <small class="text-muted">(Opsional)</small>
                        </label>

                        <textarea
                            class="form-control"
                            id="client_address"
                            name="client_address"
                            rows="3"></textarea>

                        <div class="invalid-feedback"
                            data-error="client_address">
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="mb-3">
                        <label for="client_phone" class="form-label">
                            Nomor Telepon Klien
                        </label>

                        <input
                            type="tel"
                            class="form-control"
                            id="client_phone"
                            name="client_phone"
                            minlength="10">

                        <div class="invalid-feedback"
                            data-error="client_phone">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="client_email" class="form-label">
                            Email Klien
                            <small class="text-muted">(Opsional)</small>
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="client_email"
                            name="client_email">

                        <div class="invalid-feedback"
                            data-error="client_email">
                        </div>
                    </div>

                    <!-- POC -->
                    <div class="mb-3">
                        <label for="client_poc" class="form-label">
                            Penanggung Jawab Klien (POC)
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="client_poc"
                            name="client_poc">

                        <div class="invalid-feedback"
                            data-error="client_poc">
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <h5 class="mb-3 text-success">
                        <i class="fas fa-folder-open"></i>
                        Data Project
                    </h5>

                    <!-- Nama Project -->
                    <div class="mb-3">
                        <label for="project_name" class="form-label">
                            Nama Project
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="project_name"
                            name="project_name">

                        <div class="invalid-feedback"
                            data-error="project_name">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="project_description" class="form-label">
                            Deskripsi Project
                            <small class="text-muted">(Opsional)</small>
                        </label>

                        <textarea
                            class="form-control"
                            id="project_description"
                            name="project_description"
                            rows="3"></textarea>

                        <div class="invalid-feedback"
                            data-error="project_description">
                        </div>
                    </div>

                    <!-- Link Project -->
                    <div class="mb-3">
                        <label for="project_link" class="form-label">
                            Link Project
                            <small class="text-muted">(Opsional)</small>
                        </label>

                        <input
                            type="url"
                            class="form-control"
                            id="project_link"
                            name="project_link">

                        <div class="invalid-feedback"
                            data-error="project_link">
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3">
                            <label for="project_start_date" class="form-label">
                                Tanggal Mulai Project
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="project_start_date"
                                name="project_start_date">

                            <div class="invalid-feedback"
                                data-error="project_start_date">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="project_end_date" class="form-label">
                                Tanggal Selesai Project
                                <small class="text-muted">(Opsional)</small>
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="project_end_date"
                                name="project_end_date">

                            <div class="invalid-feedback"
                                data-error="project_end_date">
                            </div>
                        </div>

                    </div>

                    <!-- Repository -->
                    <div class="mb-3">
                        <label for="project_repo" class="form-label">
                            Repository Kode (repo)
                            <small class="text-muted">(Opsional)</small>
                        </label>

                        <input
                            type="url"
                            class="form-control"
                            id="project_repo"
                            name="project_repo">

                        <div class="invalid-feedback"
                            data-error="project_repo">
                        </div>
                    </div>

                    <div class="row">


                        <div class="mb-3">
                            <label for="project_developer" class="form-label">
                                Nama Developer
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="project_developer"
                                name="project_developer">

                            <div class="invalid-feedback"
                                data-error="project_developer">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="project_developer_phone" class="form-label">
                            No. Telepon Developer
                        </label>

                        <input
                            type="tel"
                            class="form-control"
                            id="project_developer_phone"
                            name="project_developer_phone"
                            minlength="10">

                        <div class="invalid-feedback"
                            data-error="project_developer_phone">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="project_status" class="form-label">
                            Status Project
                        </label>

                        <select
                            class="form-control"
                            id="project_status"
                            name="project_status">

                            <option value="">-- Pilih Status --</option>
                            <option value="On Going">On Going</option>
                            <option value="Done">Done</option>
                        </select>

                        <div class="invalid-feedback"
                            data-error="project_status">
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<hr>
<div class="text-end">

    <a href="{{ route('clients.index') }}"
        class="btn btn-secondary">
        Batal
    </a>

    <button
        type="reset"
        class="btn btn-warning">
        Reset
    </button>

    <button
        type="submit"
        class="btn btn-primary"
        id="btnSubmitClient">

        <span class="spinner-border spinner-border-sm d-none"
            role="status"></span>

        <span id="btnSubmitClientText">
            Simpan
        </span>
    </button>
</div>
</form>
</div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        $('#project_status').select2({
            placeholder: '-- Pilih Status --',
            width: '100%'
        });

        function clearFormErrors() {
            $('#formClient .is-invalid').removeClass('is-invalid');
            $('#formClient .invalid-feedback').text('');
        }

        function showFormErrors(errors) {
            clearFormErrors();

            $.each(errors, function(field, messages) {
                $('#' + field).addClass('is-invalid');
                $('[data-error="' + field + '"]').text(messages[0]);
            });
        }

        $('#formClient').off('submit').on('submit', function(e) {
            e.preventDefault();
            clearFormErrors();

            const btn = $('#btnSubmitClient');
            btn.prop('disabled', true);
            btn.find('.spinner-border').removeClass('d-none');
            $('#btnSubmitClientText').text('Menyimpan...');

            $.ajax({
                url: "{{ route('clients.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('clients.index') }}";
                    });
                },

                error: function(xhr) {
                    if (xhr.status == 422) {
                        showFormErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                },

                complete: function() {
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').addClass('d-none');
                    $('#btnSubmitClientText').text('Simpan');
                }
            });
        });
    });
</script>
@endpush