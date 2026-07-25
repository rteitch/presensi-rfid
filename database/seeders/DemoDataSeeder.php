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

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.test'],
            [
                'name' => 'Admin Sekolah',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Teacher Users
        $guruSari = User::firstOrCreate(
            ['email' => 'guru@sekolah.test'],
            [
                'name' => 'Bu Sari',
                'password' => bcrypt('password'),
            ]
        );
        $guruSari->assignRole('guru');

        $guruBudi = User::firstOrCreate(
            ['email' => 'pakbudi@sekolah.test'],
            [
                'name' => 'Pak Budi',
                'password' => bcrypt('password'),
            ]
        );
        $guruBudi->assignRole('guru');

        // Kepala Sekolah User
        $kepsek = User::firstOrCreate(
            ['email' => 'kepsek@sekolah.test'],
            [
                'name' => 'Dr. H. Mulyadi, M.Pd (Kepala Sekolah)',
                'password' => bcrypt('password'),
            ]
        );
        $kepsek->assignRole('kepala_sekolah');

        // Classes
        $kelasA = SchoolClass::firstOrCreate([
            'nama_kelas' => 'VII-A',
            'academic_year_id' => $year->id,
        ], [
            'wali_kelas_id' => $guruSari->id,
        ]);

        $kelasB = SchoolClass::firstOrCreate([
            'nama_kelas' => 'VII-B',
            'academic_year_id' => $year->id,
        ], [
            'wali_kelas_id' => $guruBudi->id,
        ]);

        // Students
        $student1 = Student::firstOrCreate([
            'nis' => '2025001',
        ], [
            'nama' => 'Ahmad Fauzan',
            'rfid_uid' => '04A1B2C3',
            'class_id' => $kelasA->id,
            'nama_ortu' => 'Bapak Fauzan',
            'no_hp_ortu' => '081234567890',
            'status' => 'aktif',
        ]);

        $student2 = Student::firstOrCreate([
            'nis' => '2025002',
        ], [
            'nama' => 'Siti Nurhaliza',
            'rfid_uid' => '04B2C3D4',
            'class_id' => $kelasA->id,
            'nama_ortu' => 'Bapak Nurhaliza',
            'no_hp_ortu' => '081234567891',
            'status' => 'aktif',
        ]);

        $student3 = Student::firstOrCreate([
            'nis' => '2025003',
        ], [
            'nama' => 'Rizky Pratama',
            'rfid_uid' => '04C3D4E5',
            'class_id' => $kelasB->id,
            'nama_ortu' => 'Bapak Pratama',
            'no_hp_ortu' => '081234567894',
            'status' => 'aktif',
        ]);

        // Teachers (Synced with Users)
        Teacher::firstOrCreate([
            'nip' => '198501012010011001',
        ], [
            'nama' => 'Bu Sari',
            'email' => 'guru@sekolah.test',
            'user_id' => $guruSari->id,
            'no_hp' => '081234567892',
            'mata_pelajaran' => 'Matematika',
            'status' => 'aktif',
        ]);

        Teacher::firstOrCreate([
            'nip' => '198501012010011002',
        ], [
            'nama' => 'Pak Budi',
            'email' => 'pakbudi@sekolah.test',
            'user_id' => $guruBudi->id,
            'no_hp' => '081234567893',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'status' => 'aktif',
        ]);

        Device::firstOrCreate([
            'nama_device' => 'Kiosk Gerbang Utama',
        ], [
            'lokasi' => 'Gerbang Depan',
            'token_device' => Str::random(40),
            'is_active' => true,
        ]);

        // Sample Attendance Data for Today
        $today = now()->toDateString();
        Attendance::firstOrCreate([
            'student_id' => $student1->id,
            'tanggal' => $today,
        ], [
            'jam_masuk' => '07:22:00',
            'jam_pulang' => '15:05:00',
            'status' => 'terlambat',
            'keterangan' => 'Terlambat 22 menit',
        ]);

        Attendance::firstOrCreate([
            'student_id' => $student2->id,
            'tanggal' => $today,
        ], [
            'jam_masuk' => '06:55:00',
            'jam_pulang' => '15:00:00',
            'status' => 'hadir',
            'keterangan' => 'Tepat waktu',
        ]);

        Attendance::firstOrCreate([
            'student_id' => $student3->id,
            'tanggal' => $today,
        ], [
            'jam_masuk' => '06:50:00',
            'jam_pulang' => null,
            'status' => 'hadir',
            'keterangan' => 'Tepat waktu',
        ]);

        // Seed default school settings
        foreach (SchoolSetting::defaults() as $key => $value) {
            SchoolSetting::set($key, $value);
        }
    }
}
