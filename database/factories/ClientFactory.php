<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    private static ?\Faker\Generator $idFaker = null;

    /**
     * Faker khusus locale Indonesia (nama, alamat, no. telp, dsb tetap
     * masuk akal untuk konteks Indonesia meskipun config('app.faker_locale')
     * belum diset ke id_ID).
     */
    protected function idFaker(): \Faker\Generator
    {
        return static::$idFaker ??= \Faker\Factory::create('id_ID');
    }

    private const PROJECT_NAMES = [
        'Sistem Informasi Klinik',
        'Aplikasi HRIS Karyawan',
        'Sistem Manajemen Pelanggan ISP',
        'Aplikasi Manajemen Tugas Internal',
        'Sistem Reservasi Hotel',
        'Aplikasi Point of Sale Apotek',
        'Sistem Akademik Sekolah',
        'Aplikasi Absensi Face Recognition',
        'Sistem Informasi Rumah Sakit',
        'Aplikasi E-Commerce UMKM',
        'Sistem Manajemen Gudang',
        'Aplikasi Booking Lapangan Olahraga',
        'Sistem Informasi Kepegawaian',
        'Aplikasi Monitoring Proyek Konstruksi',
        'Sistem Pembayaran Online Koperasi',
    ];

    private const PROJECT_DESCRIPTIONS = [
        'Pengembangan aplikasi web untuk mempermudah proses administrasi harian klien, mulai dari pendataan hingga pelaporan.',
        'Sistem berbasis web yang mengelola data operasional perusahaan secara terpusat dan bisa diakses oleh beberapa cabang sekaligus.',
        'Aplikasi internal untuk membantu tim menyelesaikan pekerjaan lebih terstruktur, termasuk pelacakan progres dan pelaporan otomatis.',
        'Platform digital yang menggantikan proses pencatatan manual dengan sistem terkomputerisasi, lengkap dengan fitur laporan dan export data.',
        'Sistem informasi yang mengintegrasikan beberapa modul (data pelanggan, transaksi, dan laporan) dalam satu dashboard.',
        'Aplikasi yang dirancang untuk mempercepat proses bisnis klien dengan otomatisasi input data dan notifikasi real-time.',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', '-1 month');
        $isOngoing = fake()->boolean(70); // 70% project masih on going

        return [
            'client_name'             => $this->idFaker()->unique()->company(),
            'client_address'          => $this->idFaker()->address(),
            'client_phone'            => fake()->numerify('08##########'),
            'client_email'            => fake()->unique()->safeEmail(),
            'client_poc'              => $this->idFaker()->name(),
            'project_name'            => fake()->randomElement(self::PROJECT_NAMES),
            'project_description'    => fake()->randomElement(self::PROJECT_DESCRIPTIONS),
            'project_link'            => fake()->url(),
            'project_start_date'      => $startDate,
            'project_end_date'        => $isOngoing ? null : fake()->dateTimeBetween($startDate, 'now'),
            'project_repo'            => 'https://github.com/example/' . fake()->slug(2),
            'project_developer'       => $this->idFaker()->unique()->name(),
            'project_developer_phone' => fake()->numerify('08##########'),
            // Nilai disamakan dengan opsi yang sudah ada di halaman edit klien
            // ("On Going" / "Done"), supaya dropdown-nya tetap ke-select dengan benar.
            'project_status'          => $isOngoing ? 'On Going' : 'Done',
        ];
    }
}
