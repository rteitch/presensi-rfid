<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Device;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::firstOrCreate([
            'nama' => '2025/2026',
        ], [
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        AttendanceSetting::firstOrCreate([
            'academic_year_id' => $year->id,
        ], [
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '15:00:00',
            'toleransi_menit' => 15,
        ]);

        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.test'],
            ['name' => 'Admin Sekolah', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        $guruSari = User::firstOrCreate(
            ['email' => 'guru@sekolah.test'],
            ['name' => 'Bu Sari', 'password' => bcrypt('password')]
        );
        $guruSari->assignRole('guru');

        $guruBudi = User::firstOrCreate(
            ['email' => 'pakbudi@sekolah.test'],
            ['name' => 'Pak Budi', 'password' => bcrypt('password')]
        );
        $guruBudi->assignRole('guru');

        $kepsek = User::firstOrCreate(
            ['email' => 'kepsek@sekolah.test'],
            ['name' => 'Dr. H. Mulyadi, M.Pd (Kepala Sekolah)', 'password' => bcrypt('password')]
        );
        $kepsek->assignRole('kepala_sekolah');

        // Teachers
        Teacher::firstOrCreate(['nip' => '198501012010011001'], [
            'nama' => 'Bu Sari', 'email' => 'guru@sekolah.test', 'user_id' => $guruSari->id,
            'no_hp' => '081234567892', 'mata_pelajaran' => 'Matematika', 'status' => 'aktif',
        ]);
        Teacher::firstOrCreate(['nip' => '198501012010011002'], [
            'nama' => 'Pak Budi', 'email' => 'pakbudi@sekolah.test', 'user_id' => $guruBudi->id,
            'no_hp' => '081234567893', 'mata_pelajaran' => 'Bahasa Indonesia', 'status' => 'aktif',
        ]);

        // Devices
        Device::firstOrCreate(['nama_device' => 'Kiosk Gerbang Utama'], [
            'lokasi' => 'Gerbang Depan', 'token_device' => Str::random(40), 'is_active' => true,
        ]);

        // 10 Distinct Classes
        $classNames = [
            'X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPS 2', 'XII IPA 1',
            'XII IPS 1', 'VII-A', 'VIII-B', 'IX-A', 'IX-C'
        ];

        $classes = [];
        foreach ($classNames as $index => $cName) {
            $classes[] = SchoolClass::firstOrCreate(
                ['nama_kelas' => $cName, 'academic_year_id' => $year->id],
                ['wali_kelas_id' => ($index % 2 === 0) ? $guruSari->id : $guruBudi->id]
            );
        }

        // 10 Students with HD Unsplash Portrait Photos
        $studentDefs = [
            [
                'nis' => '2025001', 'nama' => 'Ahmad Fauzan', 'rfid' => '04A1B2C3', 'late_count' => 12, 'class_idx' => 0,
                'foto' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025002', 'nama' => 'Siti Nurhaliza', 'rfid' => '04B2C3D4', 'late_count' => 10, 'class_idx' => 1,
                'foto' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025003', 'nama' => 'Rizky Pratama', 'rfid' => '04C3D4E5', 'late_count' => 8, 'class_idx' => 2,
                'foto' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025004', 'nama' => 'Dewi Lestari', 'rfid' => '04D4E5F6', 'late_count' => 7, 'class_idx' => 3,
                'foto' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025005', 'nama' => 'Budi Santoso', 'rfid' => '04E5F6A7', 'late_count' => 6, 'class_idx' => 4,
                'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025006', 'nama' => 'Anisa Rahmawati', 'rfid' => '04F6A7B8', 'late_count' => 5, 'class_idx' => 5,
                'foto' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025007', 'nama' => 'Muhammad Ridwan', 'rfid' => '04A7B8C9', 'late_count' => 4, 'class_idx' => 6,
                'foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025008', 'nama' => 'Dian Sastrowardoyo', 'rfid' => '04B8C9D0', 'late_count' => 3, 'class_idx' => 7,
                'foto' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025009', 'nama' => 'Eko Prasetyo', 'rfid' => '04C9D0E1', 'late_count' => 2, 'class_idx' => 8,
                'foto' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'nis' => '2025010', 'nama' => 'Fitriani Putri', 'rfid' => '04D0E1F2', 'late_count' => 1, 'class_idx' => 9,
                'foto' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80'
            ],
        ];

        $currentMonth = now()->format('Y-m');

        foreach ($studentDefs as $sDef) {
            $student = Student::updateOrCreate(
                ['nis' => $sDef['nis']],
                [
                    'nama' => $sDef['nama'],
                    'rfid_uid' => $sDef['rfid'],
                    'class_id' => $classes[$sDef['class_idx']]->id,
                    'nama_ortu' => 'Ortu ' . $sDef['nama'],
                    'no_hp_ortu' => '0812' . rand(10000000, 99999999),
                    'foto' => $sDef['foto'],
                    'status' => 'aktif',
                ]
            );

            // Generate attendance records for the current month
            for ($day = 1; $day <= $sDef['late_count']; $day++) {
                $dateStr = sprintf('%s-%02d', $currentMonth, $day);
                Attendance::updateOrCreate(
                    ['student_id' => $student->id, 'tanggal' => $dateStr],
                    [
                        'jam_masuk' => sprintf('07:%02d:00', rand(16, 45)),
                        'jam_pulang' => '15:00:00',
                        'status' => 'terlambat',
                        'keterangan' => 'Terlambat ' . rand(16, 45) . ' menit',
                    ]
                );
            }

            for ($day = $sDef['late_count'] + 1; $day <= $sDef['late_count'] + 3; $day++) {
                if ($day > 25) break;
                $dateStr = sprintf('%s-%02d', $currentMonth, $day);
                Attendance::updateOrCreate(
                    ['student_id' => $student->id, 'tanggal' => $dateStr],
                    [
                        'jam_masuk' => sprintf('06:%02d:00', rand(40, 58)),
                        'jam_pulang' => '15:00:00',
                        'status' => 'hadir',
                        'keterangan' => 'Tepat waktu',
                    ]
                );
            }
        }

        // Seed default school settings
        foreach (SchoolSetting::defaults() as $key => $value) {
            SchoolSetting::set($key, $value);
        }
    }
}
