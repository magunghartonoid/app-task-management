@extends('sb2admin.layouts.app')

@section('title', 'Profile')

@section('content')

    <h1 class="mt-4">Profile</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    <div class="row">
        <div class="col-xl-8">

            {{-- Update Profile Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Profile Information
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-key me-1"></i>
                    Update Password
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-triangle-exclamation me-1"></i>
                    Delete Account
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

@endsection
