<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Contoh kalimat request dalam Bahasa Indonesia (Faker tidak punya
     * generator kalimat berbahasa Indonesia, jadi dibuat manual di sini).
     */
    private const REQUEST_TEXTS = [
        'Perbaikan bug validasi form pendaftaran pasien',
        'Penambahan fitur export laporan ke Excel',
        'Optimasi query DataTables supaya lebih cepat',
        'Perbaikan tampilan responsive di halaman dashboard',
        'Integrasi API pembayaran online',
        'Update tampilan menu sidebar sesuai desain baru',
        'Perbaikan bug checkbox yang tidak tersimpan',
        'Penambahan validasi input nomor telepon',
        'Perbaikan bug export PDF gagal generate',
        'Penambahan fitur notifikasi email otomatis',
        'Perbaikan bug tanggal tidak sesuai format',
        'Penambahan fitur filter data berdasarkan tanggal',
        'Optimasi loading halaman yang lambat',
        'Perbaikan bug login gagal untuk user tertentu',
        'Penambahan halaman cetak invoice',
        'Perbaikan tampilan tabel yang tidak rapi di mobile',
        'Penambahan fitur upload lampiran multi-file',
        'Perbaikan bug data duplikat saat submit form',
        'Penambahan fitur pencarian global',
        'Update logika perhitungan diskon otomatis',
        'Perbaikan bug relasi data yang tidak muncul',
        'Penambahan role & permission untuk user baru',
        'Perbaikan bug session yang cepat habis',
        'Penambahan grafik statistik di dashboard',
        'Perbaikan bug notifikasi tidak terkirim',
        'Penambahan fitur reset password lewat email',
        'Perbaikan bug pagination yang error',
        'Penambahan fitur backup data otomatis',
        'Perbaikan bug validasi tanggal deadline',
        'Penambahan integrasi WhatsApp API untuk notifikasi',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', 'now');
        $deadlineDate = fake()->boolean(90)
            ? fake()->dateTimeBetween($startDate, (clone $startDate)->modify('+60 days'))
            : null;

        $status = fake()->randomElement(['pending', 'in_progress', 'testing', 'completed', 'canceled']);

        return [
            'client_id'             => Client::factory(),
            'created_by'            => User::factory(),
            'assigned_to'           => User::factory(),
            'request'               => fake()->randomElement(self::REQUEST_TEXTS),
            'request_start_date'    => $startDate,
            'request_deadline_date' => $deadlineDate,
            'priority'              => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status'                => $status,
            'completed_at'          => $status === 'completed'
                ? fake()->dateTimeBetween($startDate, 'now')
                : null,
            'file'                  => fake()->boolean(30)
                ? 'lampiran-' . fake()->lexify('??????') . '.pdf'
                : null,
        ];
    }
}
