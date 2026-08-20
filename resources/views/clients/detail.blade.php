@extends('sb2admin.layouts.app')
@section('title', 'Detail Klien')
@section('content')

<h1 class="mt-4">Detail Klien</h1>

<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">
        <a href="{{ route('clients.index') }}">Klien</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
</ol>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-eye me-1"></i>
        Detail Data Klien
    </div>

    <div class="card-body">
        <div class="row">

            <!-- Data Client -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3">
                    Data Klien
                </h5>

                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Nama Klien</th>
                        <td>{{ $client->client_name }}</td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td>{{ $client->client_address ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Telepon</th>
                        <td>{{ $client->client_phone }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $client->client_email ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>POC</th>
                        <td>{{ $client->client_poc }}</td>
                    </tr>
                </table>
            </div>

            <!-- Data Project -->
            <div class="col-md-6">
                <h5 class="text-success mb-3">
                    Data Project
                </h5>

                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Nama Project</th>
                        <td>{{ $client->project_name }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $client->project_description ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Link</th>
                        <td>@if($client->project_link)
                            <a href="{{ $client->project_link }}" target="_blank">
                                {{ $client->project_link }}
                            </a>
                            @else 
                            -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ $client->project_start_date }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ $client->project_end_date ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Repository</th>
                        <td>@if($client->project_repo)
                            <a href="{{ $client->project_repo }}" target="_blank">
                                {{ $client->project_repo }}
                            </a>
                            @else 
                            -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Developer</th>
                        <td>{{ $client->project_developer }}</td>
                    </tr>

                    <tr>
                        <th>No. Developer</th>
                        <td>{{ $client->project_developer_phone }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{{ $client->project_status }}</td>
                    </tr>
                </table>
        </div>
            <hr class="my-6 border-t border-gray-300" />

            <!-- Request -->
             <h5 class="mb-3">Data Request</h5>

                @if($client->request->count())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Created By</th>
                                <th>Assigned To</th>
                                <th>Request</th>
                                <th>Tanggal Mulai</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->request as $request)
                                <tr>
                                    <td>{{ $request->createdBy->name }}</td>
                                    <td>{{ $request->assignedTo->name }}</td>
                                    <td>{{ $request->request }}</td>
                                    <td>{{ $request->request_start_date }}</td>
                                    <td>{{ $request->request_deadline_date }}</td>
                                    <td>@if($request->priority == 'urgent')
                                        <span class="badge bg-danger">Urgent</span>
                                        @elseif($request->priority == 'high')
                                        <span class="badge" style="background-color:#fd7e14;color:#fff">High</span>
                                        @elseif($request->priority == 'medium')
                                        <span class="badge bg-warning">Medium</span>
                                        @else($request->priority == 'low')
                                        <span class="badge bg-info">Low</span>
                                        @endif
                                    </td>
                                    <td>@if($request->status == 'pending')
                                        <span class="badge bg-secondary">Pending</span>
                                        @elseif($request->status == 'in_progress')
                                        <span class="badge" style="background-color:#0d6efd;color:#fff">In Progress</span>
                                        @elseif($request->status == 'testing')
                                        <span class="badge bg-warning">Testing</span>
                                        @elseif($request->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @else($request->status == 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                        @endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Belum ada request.</p>
                @endif
            </div>

        <div class="text-end">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                Kembali
            </a>

            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">
                <i class="fas fa-pen"></i>
            </a>

        </div>
    </div>
</div>
@endsection