<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksiMaterialTable extends Migration
{
    public function up()
    {
        Schema::create('detail_transaksi_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi_material')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_transaksi_material');
    }
}