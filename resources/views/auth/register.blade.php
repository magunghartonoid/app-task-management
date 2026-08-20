@extends('layouts.appp')
@section('content')
    <div class="container p-5">
        <div class="row rounded-4 overflow-hidden shadow-lg"
            style="background: linear-gradient(135deg,#0f172a,#374151); min-height:600px;">

            <!-- kiri -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center p-5">
                <div class="card shadow-lg border-0 rounded-4 p-4" style="filter: box-shadow(0 10px 20px rgba(0,0,0,.45));">

                    <h2 class="text-center fw-bold mb-4"> REGISTER </h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Name"
                                    value="{{ old('name') }}" required>

                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" placeholder="Username"
                                    value="{{ old('username') }}" required>

                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Password" required>

                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password','eye1')">
                                        <i id="eye1" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" placeholder="Confirm Password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation','eye2')">
                                        <i id="eye2" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <input type="email" class="form-control" name="email" placeholder="Email"
                                value="{{ old('email') }}" required>
                        </div>

                        <!-- Tombol -->
                        <div class="text-end">
                            <button class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kanan -->
            <div class="col-lg-6 text-white d-flex flex-column justify-content-center align-items-center text-center p-5">

                <img src="{{ asset('images/adiva.png') }}" alt="Logo" width="180" class="mb-4"
                    style="filter: drop-shadow(0 10px 20px rgba(0,0,0,.45));">
                <h1 class="fw-bold mb-3">
                    TASK MANAGEMENT
                </h1>

                <p class="w-75">
                    untuk mencatat permintaan klien dan memantau status pengerjaan proyek.
                </p>

                <p class="mt-5 mb-0">
                    Created By Alfahrezi © 2026
                </p>

            </div>

        </div>
    </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            let input = document.getElementById(id);
            let eye = document.getElementById(icon);

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
@endsection
