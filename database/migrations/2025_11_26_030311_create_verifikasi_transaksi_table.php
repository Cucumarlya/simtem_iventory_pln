<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifikasiTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('verifikasi_transaksi', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi
            $table->foreignId('transaksi_id')
                ->constrained('transaksi_material')
                ->onDelete('cascade');

            // Admin yang melakukan verifikasi
            $table->foreignId('diverifikasi_oleh')
                ->nullable() // Penting: admin yg buat transaksi → langsung disetujui
                ->constrained('users')
                ->onDelete('restrict');

            $table->timestamp('tanggal_verifikasi')->useCurrent();

            // Status history
            $table->enum('status', ['menunggu','disetujui','dikembalikan']);

            $table->text('alasan_pengembalian')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('verifikasi_transaksi');
    }
}