<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
     * Check if user is kepala gudang (super admin)
     */
    public function isKepalaGudang(): bool
    {
        return $this->role === 'super_adm';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user has access to a specific feature
     */
    public function hasAccess(string $feature): bool
    {
        return match ($feature) {
            'dashboard' => in_array($this->role, ['super_adm', 'admin']),
            'barang_masuk' => true, // All roles can access
            'barang_keluar' => true, // All roles can access
            'analisis' => in_array($this->role, ['super_adm', 'admin']),
            'laporan' => $this->role === 'super_adm',
            'manajemen_pengguna' => $this->role === 'super_adm',
            default => false,
        };
    }
}
