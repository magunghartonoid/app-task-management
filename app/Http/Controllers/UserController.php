<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
     * GET /users/data
     */
    public function data(Request $request)
    {
        $query = User::query()->select(['id', 'name', 'username', 'email', 'created_at']);

        // Pencarian global dari DataTables
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // Ordering
        $columns = ['id', 'name', 'username', 'email', 'created_at'];
        if ($request->filled('order.0.column')) {
            $orderColIndex = (int) $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir', 'asc');
            if (isset($columns[$orderColIndex])) {
                $query->orderBy($columns[$orderColIndex], $orderDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination DataTables
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $users = $query->skip($start)->take($length)->get();

        $data = $users->map(function ($user, $index) use ($start) {
            return [
                'DT_RowId' => 'row_' . $user->id,
                'no'       => $start + $index + 1,
                'id'       => $user->id,
                'name'     => e($user->name),
                'username' => e($user->username),
                'email'    => e($user->email),
                'aksi'     => view('users.partials.aksi', compact('user'))->render(),
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
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

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
