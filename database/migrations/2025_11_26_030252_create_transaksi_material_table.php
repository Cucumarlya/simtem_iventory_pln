<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiMaterialTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_material', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal');
            $table->enum('jenis', ['penerimaan','pengeluaran']);
            $table->string('nama_pihak_transaksi');
            $table->enum('keperluan', ['YANBUNG','P2TL','GANGGUAN','PLN']);
            $table->string('nomor_pelanggan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->string('foto_sr_sebelum')->nullable();
            $table->string('foto_sr_sesudah')->nullable();

            // User yang membuat transaksi
            $table->foreignId('dibuat_oleh')
                ->constrained('users')
                ->onDelete('restrict');

            // Status utama transaksi
            $table->enum('status', ['menunggu','disetujui','dikembalikan'])
                ->default('menunggu');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_material');
    }
}