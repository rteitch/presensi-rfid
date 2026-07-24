<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rfid_logs', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('rfid_uid')->constrained('students')->nullOnDelete();
        });

        // Mapping existing rfid_logs to student_id
        DB::statement('
            UPDATE rfid_logs 
            JOIN students ON rfid_logs.rfid_uid = students.rfid_uid 
            SET rfid_logs.student_id = students.id
            WHERE rfid_logs.student_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfid_logs', function (Blueprint $table) {
            //
        });
    }
};
