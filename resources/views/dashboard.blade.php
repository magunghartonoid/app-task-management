@extends('sb2admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    {{-- Konten asli dashboard (bukan konten demo SB Admin) --}}
    <div class="card mb-4">
        <div class="card-body">
            {{ __("You're logged in!") }}
        </div>
    </div>

@endsection
