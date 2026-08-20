@extends('layouts.appp')
@section('content')
    <div class="container p-5">
        <div class="row rounded-4 overflow-hidden shadow-lg"
            style="background: linear-gradient(135deg,#0f172a,#374151); min-height:600px;">

            <!--kiri-->
            <div class="col-lg-6 text-white d-flex flex-column justify-content-center align-items-center text-center p-5">
                <img src="{{ asset('images/adiva.png') }}" alt="Logo" width="180" class="mb-4"
                    style="filter: drop-shadow(0 10px 20px rgba(0,0,0,.45));">

                <h1 class="fw-bold mb-3">
                    TASK MANAGEMENT
                </h1>

                <p class="w-75">
                    Sistem untuk mencatat permintaan klien dan memantau status pengerjaan proyek.
                </p>
                <p class="mt-5 mb-0">
                    Created By Alfahrezi © 2026
                </p>
            </div>

            <!--kanan-->
            <div class="col-lg-6 d-flex justify-content-center align-items-center p-5">
                <div class="card shadow-lg border-0 rounded-4 p-4" style="filter: box-shadow(0 10px 20px rgba(0,0,0,.45));">

                    <h2 class="text-center fw-bold mb-4">
                        LOGIN
                    </h2>
                    <x-auth-session-status class="mb-3" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Username -->
                        <div class="mb-3">

                            <label for="username" class="form-label">Username</label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </span>

                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username') }}" required autofocus>
                            </div>

                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">

                            <label for="password" class="form-label">Password</label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan Password" required>

                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>

                            @error('password')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">

                                <label class="form-check-label" for="remember"> Remember me
                                </label>
                            </div>

                        </div>

                        <!-- Button -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>
                            Sign In
                        </button>
                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"> Forgot Your Password? </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
@endsection
