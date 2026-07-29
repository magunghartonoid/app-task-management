<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Tampilkan halaman index (tabel diisi via AJAX/DataTables).
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Endpoint server-side untuk DataTables (dipanggil via AJAX).
     * Menggunakan package Yajra\DataTables — search, sort, dan pagination
     * sudah ditangani otomatis oleh package ini.
     * GET /users/data
     */
    public function data(Request $request)
    {
        $query = User::query()->select(['id', 'name', 'username', 'email', 'photo']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('photo', function ($user) {
                return '<img src="' . $user->photo_url . '" alt="' . e($user->name) . '" '
                    . 'class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">';
            })
            ->addColumn('aksi', function ($user) {
                return view('users.partials.aksi', compact('user'))->render();
            })
            ->rawColumns(['photo', 'aksi'])
            ->make(true);
    }

    /**
     * Tampilkan halaman form tambah user (halaman terpisah, bukan modal).
     * GET /users/create
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan user baru. POST /users (dipanggil via AJAX dari halaman users/create)
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Kalau ada file foto yang diupload, simpan ke storage/app/public/photos
        if ($request->hasFile('photo')) {
            $user->photo = $this->storePhoto($request->file('photo'));
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan.',
            'data'    => $user,
        ]);
    }

    /**
     * Tampilkan halaman form edit user (halaman terpisah, bukan modal).
     * GET /users/{user}/edit
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user. PUT /users/{user} (dipanggil via AJAX dari halaman users/edit)
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];

        // Password hanya diubah kalau field-nya diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Kalau user upload foto baru, hapus foto lama (kalau ada) lalu simpan yang baru
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete('photos/' . $user->photo);
            }
            $user->photo = $this->storePhoto($request->file('photo'));
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data'    => $user,
        ]);
    }

    /**
     * Hapus user. DELETE /users/{user} (AJAX)
     */
    public function destroy(Request $request, User $user)
    {
        // Cegah user menghapus akun dirinya sendiri
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa menghapus akun Anda sendiri.',
            ], 422);
        }

        // Hapus file foto profil (kalau ada) supaya tidak jadi file sampah
        if ($user->photo) {
            Storage::disk('public')->delete('photos/' . $user->photo);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    /**
     * Helper: simpan file foto upload ke storage/app/public/photos
     * dan kembalikan nama filenya saja (yang disimpan di kolom `photo`).
     */
    private function storePhoto($file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('photos', $filename, 'public');

        return $filename;
    }
}
