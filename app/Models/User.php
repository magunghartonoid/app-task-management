<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'username', 'photo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor: URL foto profil user.
     * Kalau user belum punya foto, kembalikan avatar default (UI Avatars,
     * dibuat otomatis dari inisial nama, tidak perlu file apa pun).
     *
     * Dipakai di Blade dengan: $user->photo_url
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists('photos/' . $this->photo)) {
            return asset('storage/photos/' . $this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4e73df&color=fff';
    }
}
