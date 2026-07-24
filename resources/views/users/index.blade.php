@extends('sb2admin.layouts.app')

@section('title', 'Manajemen User')

@push('styles')
    {{-- DataTables - styling Bootstrap 5 - CDNJS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.css">
    {{-- SweetAlert2 - CDNJS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">

    <style>
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 6px;
        }
    </style>
@endpush

@section('content')

    <h1 class="mt-4">Manajemen User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Users</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> Daftar User</span>
            <button type="button" class="btn btn-primary btn-sm" id="btnAddUser">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle" id="tableUsers" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============== MODAL FORM (Create & Edit) ============== --}}
    <div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUser">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <input type="hidden" id="form_method" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUserLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

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
                            <label for="password" class="form-label">
                                Password
                                <small class="text-muted" id="passwordHint" style="display:none;">
                                    (kosongkan jika tidak ingin mengubah password)
                                </small>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                            <div class="invalid-feedback" data-error="password"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitUser">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="btnSubmitUserText">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- jQuery (dibutuhkan DataTables) - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- DataTables core (WAJIB dimuat sebelum addon styling) - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/2.2.2/dataTables.min.js"></script>
    {{-- DataTables addon styling Bootstrap 5 - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.8/dataTables.bootstrap5.min.js"></script>
    {{-- SweetAlert2 - CDNJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>

    <script>
        $(function () {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });

            const routes = {
                data: "{{ route('users.data') }}",
                store: "{{ route('users.store') }}",
                edit: (id) => "{{ url('users') }}/" + id + "/edit",
                update: (id) => "{{ url('users') }}/" + id,
                destroy: (id) => "{{ url('users') }}/" + id,
            };

            // ============== DATATABLE ==============
            const table = $('#tableUsers').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: routes.data,
                    type: 'GET'
                },
                columns: [
                    { data: 'no', name: 'no', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
                language: {
                    processing: "Memuat data...",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Belum ada data",
                    search: "Cari:",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" },
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)"
                }
            });

            // ============== HELPER: loading di tombol ==============
            function setButtonLoading(btn, loading, loadingText = 'Memproses...') {
                const $btn = $(btn);
                if (loading) {
                    $btn.data('original-html', $btn.html());
                    $btn.prop('disabled', true);
                    $btn.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingText
                    );
                } else {
                    $btn.prop('disabled', false);
                    $btn.html($btn.data('original-html'));
                }
            }

            function clearFormErrors() {
                $('#formUser .is-invalid').removeClass('is-invalid');
                $('#formUser .invalid-feedback').text('');
            }

            function showFormErrors(errors) {
                clearFormErrors();
                $.each(errors, function (field, messages) {
                    const $input = $('#' + field);
                    $input.addClass('is-invalid');
                    $('[data-error="' + field + '"]').text(messages[0]);
                });
            }

            // ============== TAMBAH USER ==============
            $('#btnAddUser').on('click', function () {
                clearFormErrors();
                $('#formUser')[0].reset();
                $('#user_id').val('');
                $('#form_method').val('POST');
                $('#modalUserLabel').text('Tambah User');
                $('#passwordHint').hide();
                $('#password').prop('required', true);
                $('#modalUser').modal('show');
            });

            // ============== EDIT USER (ambil data dulu) ==============
            $('#tableUsers').on('click', '.btn-edit-user', function () {
                const id = $(this).data('id');
                const $btn = $(this);

                setButtonLoading($btn, true, '');

                $.ajax({
                    url: routes.edit(id),
                    type: 'GET',
                }).done(function (res) {
                    if (res.success) {
                        clearFormErrors();
                        $('#formUser')[0].reset();
                        $('#user_id').val(res.data.id);
                        $('#form_method').val('PUT');
                        $('#name').val(res.data.name);
                        $('#username').val(res.data.username);
                        $('#email').val(res.data.email);
                        $('#modalUserLabel').text('Edit User');
                        $('#passwordHint').show();
                        $('#password').prop('required', false);
                        $('#modalUser').modal('show');
                    }
                }).fail(function () {
                    Swal.fire('Gagal', 'Tidak bisa memuat data user.', 'error');
                }).always(function () {
                    setButtonLoading($btn, false);
                });
            });

            // ============== SUBMIT FORM (CREATE / UPDATE) ==============
            $('#formUser').on('submit', function (e) {
                e.preventDefault();

                const id = $('#user_id').val();
                const method = $('#form_method').val();
                const isUpdate = method === 'PUT';

                const url = isUpdate ? routes.update(id) : routes.store;

                const payload = {
                    name: $('#name').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                };

                if (isUpdate) {
                    payload._method = 'PUT';
                }

                const $submitBtn = $('#btnSubmitUser');
                $submitBtn.prop('disabled', true);
                $submitBtn.find('.spinner-border').removeClass('d-none');
                $('#btnSubmitUserText').text('Menyimpan...');

                $.ajax({
                    url: url,
                    type: 'POST', // method spoofing via _method utk PUT
                    data: payload,
                }).done(function (res) {
                    if (res.success) {
                        $('#modalUser').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                }).fail(function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        showFormErrors(errors);
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
                    }
                }).always(function () {
                    $submitBtn.prop('disabled', false);
                    $submitBtn.find('.spinner-border').addClass('d-none');
                    $('#btnSubmitUserText').text('Simpan');
                });
            });

            // ============== DELETE USER (dengan konfirmasi SweetAlert) ==============
            $('#tableUsers').on('click', '.btn-delete-user', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const $btn = $(this);

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    html: 'User <b>' + name + '</b> akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        setButtonLoading($btn, true, '');

                        $.ajax({
                            url: routes.destroy(id),
                            type: 'POST',
                            data: { _method: 'DELETE' },
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
                            const msg = xhr.responseJSON?.message || 'Gagal menghapus user.';
                            Swal.fire('Gagal', msg, 'error');
                            setButtonLoading($btn, false);
                        });
                    }
                });
            });

            // Reset validasi tiap modal ditutup
            $('#modalUser').on('hidden.bs.modal', function () {
                clearFormErrors();
            });
        });
    </script>
@endpush
