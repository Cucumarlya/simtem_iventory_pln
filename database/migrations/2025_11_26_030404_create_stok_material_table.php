<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokMaterialTable extends Migration
{
    public function up()
    {
        Schema::create('stok_material', function (Blueprint $table) {
            $table->id();

            // FK material
            $table->foreignId('material_id')
                ->constrained('materials')
                ->onDelete('restrict');

            $table->date('tanggal');

            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);

            // FK transaksi → boleh null (misal stok awal)
            $table->foreignId('transaksi_id')
                ->nullable()
                ->constrained('transaksi_material')
                ->nullOnDelete();

            // Perbaikan: default agar tidak error saat insert penerimaan/pengeluaran
            $table->integer('stok_akhir')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_material');
    }
}