# InternHub

InternHub adalah aplikasi manajemen presensi dan logbook peserta magang berbasis Laravel.

Project ini mendukung multi role (`admin`, `mentor`, `magang`, `alumni`) dengan fokus utama:

- Presensi harian berbasis validasi lokasi kantor
- Logbook aktivitas peserta
- Monitoring peserta oleh admin/mentor
- Pengaturan jam kerja dan hari kerja khusus

## Fitur Utama

- Presensi masuk/pulang dengan validasi koordinat lokasi.
- Auto checkout pada `23:59` (jika peserta belum absen pulang).
- Kalender presensi bulanan (magang dan admin).
- Detail presensi per tanggal + detail logbook terkait.
- Manajemen peserta magang (aktif dan arsip).
- Manajemen akun admin/mentor (CRUD).
- Notifikasi toast global (success/error/info).
- Dark mode (`light`, `dark`, `system`) di area dashboard.

## Teknologi

- PHP 8+
- Laravel
- MySQL/MariaDB
- Blade + Tailwind CSS
- Alpine.js (interaksi UI sederhana)
- Vite (asset build)

## Instalasi

1. Clone repository

```bash
git clone <repo-url>
cd internhub
```

2. Install dependency backend dan frontend

```bash
composer install
npm install
```

3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

4. Atur koneksi database di `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=internhub
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

6. Jalankan aplikasi

```bash
php artisan serve
npm run dev
```

Akses aplikasi di `http://127.0.0.1:8000`.

## Scheduler (Wajib Untuk Auto Checkout)

Jalankan scheduler agar auto checkout bekerja.

Development:

```bash
php artisan schedule:run
```

Production (cron):

```bash
* * * * * cd /path/to/internhub && php artisan schedule:run >> /dev/null 2>&1
```

Command manual untuk uji auto checkout:

```bash
php artisan presensi:auto-checkout
```

## Struktur Role

- `admin`: akses dashboard admin, manajemen peserta, settings, manajemen admin/mentor.
- `mentor`: akses monitoring peserta sesuai batas role.
- `magang`: akses absensi, logbook, profil.
- `alumni`: data peserta yang sudah selesai masa magang.

## Rute Penting

- Magang:
	- `/magang/attendance`
	- `/magang/logbook`
	- `/magang/profile`
- Admin:
	- `/dashboard`
	- `/admin/peserta/detail`
	- `/admin/laporan/presensi`
	- `/admin/settings`
	- `/admin/management`

## Testing

Menjalankan seluruh test:

```bash
php artisan test
```

Menjalankan test spesifik:

```bash
php artisan test --filter=AdminManagementCrudTest
```

## Catatan Pengembangan

- Gunakan timezone `Asia/Makassar` untuk fitur presensi.
- Format waktu presensi disajikan dalam `HH:mm`.
- Untuk perubahan UI modal/toast global, cek layout:
	- `resources/views/layouts/admin.blade.php`
	- `resources/views/layouts/magang.blade.php`
	- `public/js/notifications.js`

## Lisensi

Project ini menggunakan lisensi MIT.
