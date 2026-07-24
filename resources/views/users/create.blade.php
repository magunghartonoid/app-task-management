@extends('sb2admin.layouts.app')

@section('title', 'Tambah User')

@push('styles')
    {{-- SweetAlert2 - CDNJS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">
@endpush

@section('content')

    <h1 class="mt-4">Tambah User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i> Form Tambah User
        </div>
        <div class="card-body">

            <form id="formUser">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="invalid-feedback" data-error="name"></div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                    <div class="invalid-feedback" data-error="username"></div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback" data-error="email"></div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                    <div class="invalid-feedback" data-error="password"></div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                </div>

                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btnSubmitUser">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span id="btnSubmitUserText">Simpan</span>
                </button>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
    {{-- jQuery - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- SweetAlert2 - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    <script>
        $(function () {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });

            function clearFormErrors() {
                $('#formUser .is-invalid').removeClass('is-invalid');
                $('#formUser .invalid-feedback').text('');
            }

            function showFormErrors(errors) {
                clearFormErrors();
                $.each(errors, function (field, messages) {
                    $('#' + field).addClass('is-invalid');
                    $('[data-error="' + field + '"]').text(messages[0]);
                });
            }

            $('#formUser').on('submit', function (e) {
                e.preventDefault();
                clearFormErrors();

                const $submitBtn = $('#btnSubmitUser');
                $submitBtn.prop('disabled', true);
                $submitBtn.find('.spinner-border').removeClass('d-none');
                $('#btnSubmitUserText').text('Menyimpan...');

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                }).done(function (res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(function () {
                            window.location.href = "{{ route('users.index') }}";
                        });
                    }
                }).fail(function (xhr) {
                    if (xhr.status === 422) {
                        showFormErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
                    }
                }).always(function () {
                    $submitBtn.prop('disabled', false);
                    $submitBtn.find('.spinner-border').addClass('d-none');
                    $('#btnSubmitUserText').text('Simpan');
                });
            });
        });
    </script>
@endpush
