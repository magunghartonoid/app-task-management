@extends('sb2admin.layouts.app')
@section('title', 'Tambah Request')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.min.css">
@endpush
@section('content')

<h1 class="mt-4">Tambah Request</h1>

<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">
        <a href="{{ route('requests.index') }}">Request</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
</ol>

<div class="card">
    <div class="card-header">
        <i class="fas fa-tasks me-1"></i>
        Form Tambah Request
    </div>

    <div class="card-body">

        <form id="formRequest" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Klien</label>
                        <select class="form-control" id="client_id" name="client_id">
                            <option value="">-- Pilih klien --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->client_name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="invalid-feedback" data-error="client_id"></div>
                    </div>

                    <div class="mb-3">
                        <label>Created By</label>
                        <select class="form-control" id="created_by" name="created_by">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="invalid-feedback" data-error="created_by"></div>
                    </div>

                    <div class="mb-3">
                        <label>Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            <option value="">-- Pilih Developer --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="invalid-feedback" data-error="assigned_to"></div>
                    </div>

                    <div class="mb-3">
                        <label>Request</label>
                        <textarea
                            class="form-control"
                            id="request"
                            name="request"
                            rows="8"></textarea>
                        <div class="invalid-feedback" data-error="request"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Tanggal Mulai</label>
                        <input
                            type="date"
                            class="form-control"
                            id="request_start_date"
                            name="request_start_date">
                        <div class="invalid-feedback" data-error="request_start_date"></div>
                    </div>

                    <div class="mb-3">
                        <label>Deadline</label>
                        <input
                            type="date"
                            class="form-control"
                            id="request_deadline_date"
                            name="request_deadline_date">
                        <div class="invalid-feedback" data-error="request_deadline_date"></div>
                    </div>

                    <div class="mb-3">
                        <label>Priority</label>
                        <select
                            class="form-control"
                            id="priority"
                            name="priority">
                            <option value="">-- Pilih Priority --</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>

                        <div class="invalid-feedback" data-error="priority"></div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select
                            class="form-control"
                            id="status"
                            name="status">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="testing">Testing</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                        </select>

                        <div class="invalid-feedback" data-error="status"></div>
                    </div>

                    <div class="mb-3">
                        <label>Lampiran</label>
                        <input
                            type="file"
                            class="form-control"
                            id="file"
                            name="file">
                        <div class="invalid-feedback" data-error="file"></div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btnSubmitRequest">
                    <span class="spinner-border spinner-border-sm d-none"></span>
                    <span id="btnSubmitRequestText">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.26.25/sweetalert2.all.min.js"></script>
<script>
$(function () {

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $('#client_id').select2({
        placeholder: '-- Pilih Klien --',
        width: '100%'
    });

    $('#created_by').select2({
        placeholder: '-- Pilih User --',
        width: '100%'
    });

    $('#assigned_to').select2({
        placeholder: '-- Pilih Developer --',
        width: '100%'
    });

    $('#priority').select2({
        placeholder: '-- Pilih Priority --',
        width: '100%'
    });

    $('#status').select2({
        placeholder: '-- Pilih Status --',
        width: '100%'
    });

    function clearFormErrors() {
        $('#formRequest .is-invalid').removeClass('is-invalid');
        $('#formRequest .invalid-feedback').text('');
    }

    function showFormErrors(errors) {
        clearFormErrors();
        $.each(errors, function(field, messages) {
            $('#' + field).addClass('is-invalid');
            $('[data-error="' + field + '"]').text(messages[0]);
        });
    }

    $('#formRequest').off('submit').on('submit', function(e){
        e.preventDefault();
        clearFormErrors();
        const btn = $('#btnSubmitRequest');
        btn.prop('disabled', true);
        btn.find('.spinner-border').removeClass('d-none');
        $('#btnSubmitRequestText').text('Menyimpan...');
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('requests.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer:1500,
                    showConfirmButton:false
                }).then(function(){
                    window.location.href="{{ route('requests.index') }}";
                });
            },

            error:function(xhr){
                if(xhr.status==422){
                    showFormErrors(xhr.responseJSON.errors);
                }else{
                    Swal.fire({
                        icon:'error',
                        title:'Gagal',
                        text:'Terjadi kesalahan pada server.'
                    });
                }
            },

            complete:function(){
                btn.prop('disabled',false);
                btn.find('.spinner-border').addClass('d-none');
                $('#btnSubmitRequestText').text('Simpan');
            }
        });
    });
});
</script>
@endpush