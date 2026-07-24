<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_logs', function (Blueprint $table) {
            $table->id();
            $table->string('rfid_uid');
            $table->foreignId('device_id')->nullable()->constrained('devices')->nullOnDelete();
            $table->boolean('is_valid')->default(false);
            $table->string('keterangan')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_logs');
    }
};
