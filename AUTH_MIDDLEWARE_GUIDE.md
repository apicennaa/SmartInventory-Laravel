# Panduan Auth Middleware dengan Fortify

## Instalasi & Setup

### 1. Install Dependencies
```bash
composer require laravel/fortify
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

### 2. Jalankan Migration
```bash
php artisan migrate
```

### 3. Seed User Contoh (Opsional)
```bash
php artisan db:seed --class=UserSeeder
```

## User Contoh

Setelah menjalankan seeder, Anda dapat login dengan:

- **Kepala Gudang (Super Admin)**
  - Email: `kepalagudang@example.com`
  - Password: `password`
  - Role: `super_adm`

- **Admin**
  - Email: `admin@example.com`
  - Password: `password`
  - Role: `admin`

- **Staff**
  - Email: `staff@example.com`
  - Password: `password`
  - Role: `staff`

## Struktur Role & Akses

### Kepala Gudang (super_adm)
✅ Akses penuh ke semua fitur:
- Dashboard
- Barang Masuk
- Barang Keluar
- Analisis
- Laporan
- Manajemen Pengguna

### Admin (admin)
✅ Akses ke:
- Dashboard
- Barang Masuk
- Barang Keluar
- Analisis

❌ Tidak bisa mengakses:
- Laporan
- Manajemen Pengguna

### Staff
✅ Akses terbatas:
- Barang Masuk
- Barang Keluar

❌ Tidak bisa mengakses:
- Dashboard
- Analisis
- Laporan
- Manajemen Pengguna

## Cara Menggunakan Middleware

### Di Routes
```php
// Single role
Route::middleware(['auth', 'role:super_adm'])->group(function () {
    // Routes hanya untuk super admin
});

// Multiple roles
Route::middleware(['auth', 'role:super_adm,admin'])->group(function () {
    // Routes untuk super admin dan admin
});

// Semua role
Route::middleware(['auth', 'role:super_adm,admin,staff'])->group(function () {
    // Routes untuk semua role
});
```

### Di Controller
```php
use App\Http\Middleware\RoleMiddleware;

// Di constructor
public function __construct()
{
    $this->middleware(['auth', 'role:super_adm,admin']);
}
```

### Di Blade View
```blade
@auth
    @if(auth()->user()->isKepalaGudang())
        <a href="{{ route('manajemen-pengguna.index') }}">Manajemen Pengguna</a>
    @endif

    @if(auth()->user()->hasAccess('dashboard'))
        <a href="{{ route('dashboard') }}">Dashboard</a>
    @endif
@endauth
```

## Helper Methods di User Model

### Check Role
```php
$user->isKepalaGudang(); // true/false
$user->isAdmin();         // true/false
$user->isStaff();         // true/false
```

### Check Access
```php
$user->hasAccess('dashboard');           // true/false
$user->hasAccess('barang_masuk');        // true/false
$user->hasAccess('barang_keluar');       // true/false
$user->hasAccess('analisis');            // true/false
$user->hasAccess('laporan');             // true/false
$user->hasAccess('manajemen_pengguna');  // true/false
```

## Routes yang Tersedia

### Authentication (Fortify)
- `/login` - Halaman login
- `/register` - Halaman registrasi
- `/logout` - Logout
- `/forgot-password` - Lupa password
- `/reset-password` - Reset password

### Dashboard
- `/dashboard` - Halaman dashboard (super_adm, admin)

### Barang Masuk
- `/barang-masuk` - Daftar barang masuk (semua role)
- `/barang-masuk/create` - Tambah barang masuk
- `/barang-masuk/{id}` - Detail barang masuk
- `/barang-masuk/{id}/edit` - Edit barang masuk

### Barang Keluar
- `/barang-keluar` - Daftar barang keluar (semua role)
- `/barang-keluar/create` - Tambah barang keluar
- `/barang-keluar/{id}` - Detail barang keluar
- `/barang-keluar/{id}/edit` - Edit barang keluar

### Analisis
- `/analisis` - Halaman analisis (super_adm, admin)
- `/analisis/laporan-stok` - Laporan stok
- `/analisis/trend-barang` - Trend barang

### Laporan
- `/laporan` - Halaman laporan (super_adm saja)
- `/laporan/barang-masuk` - Laporan barang masuk
- `/laporan/barang-keluar` - Laporan barang keluar
- `/laporan/stok` - Laporan stok
- `/laporan/export` - Export laporan

### Manajemen Pengguna
- `/manajemen-pengguna` - Daftar pengguna (super_adm saja)
- `/manajemen-pengguna/create` - Tambah pengguna
- `/manajemen-pengguna/{id}` - Detail pengguna
- `/manajemen-pengguna/{id}/edit` - Edit pengguna

## Catatan Penting

1. **Default Role**: Saat registrasi, user baru akan mendapat role `staff` secara default
2. **Redirect After Login**: User akan di-redirect berdasarkan role mereka setelah login
3. **Middleware Protection**: Semua routes yang memerlukan autentikasi sudah dilindungi dengan middleware `auth` dan `role`
4. **403 Error**: Jika user tidak memiliki akses, akan mendapat error 403 (Unauthorized)

## Troubleshooting

### Middleware tidak bekerja
Pastikan middleware sudah terdaftar di `bootstrap/app.php`:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

### Fortify tidak terdeteksi
Pastikan `FortifyServiceProvider` sudah terdaftar di `bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
];
```

### User tidak bisa login
Pastikan migration sudah dijalankan dan kolom `role` sudah ada di tabel `users`.

