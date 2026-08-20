<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index() 
    {
        return view('users.index');
    }

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

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'      => $validated['name'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
        ]);

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

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];

        // Password hanya diubah kalau fieldnyd diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                storage::disk('public')->delete('photos/' . $user->photo);
            }
            $user->photo = $this->storePhoto($request->file('photo'));
            $user->save();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'User berhasil diperbarui.',
            'data'      => $user,
        ]);

    }

    public function destroy(Request $request, User $user)
    {
        // cegah user menghapus akun dirinya sendiri
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'success'  => false,
                'message'  => 'Anda tidak bisa menghapus akun Anda sendiri.',
            ], 422);
        }

        if ($user->photo) Storage::disk('public')->delete('photos/' . $user->photo);

        $user->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'User berhasil dihapus.'
        ]);
    }

    private function storePhoto($file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('photos', $filename, 'public');
        return $filename;
    }

}
