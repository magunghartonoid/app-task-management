<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk membuat user baru.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo'    => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan error custom (Bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.unique'   => 'Username sudah digunakan.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'photo.image'       => 'File foto harus berupa gambar.',
            'photo.mimes'       => 'Format foto harus jpeg, jpg, png, atau webp.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ];
    }

    /**
     * Response custom saat validasi gagal (khusus request AJAX/JSON)
     * supaya bisa ditangkap SweetAlert di frontend.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => $validator->errors(),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
