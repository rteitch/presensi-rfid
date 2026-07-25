<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah nilai enum baru: pulang_cepat, dispensasi pada kolom status attendances.
     */
    public function up(): void
    {
        // MySQL: ENUM tidak bisa diubah via $table->enum() jika sudah ada data,
        // gunakan raw SQL agar aman tanpa DROP/recreate.
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir','terlambat','izin','pulang_cepat','dispensasi','sakit','alpha') NOT NULL DEFAULT 'hadir'");
    }

    public function down(): void
    {
        // Rollback: kembalikan ke 5 nilai asal (baris dengan status baru akan menjadi NULL / gagal jika ada)
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir','terlambat','izin','sakit','alpha') NOT NULL DEFAULT 'hadir'");
    }
};
