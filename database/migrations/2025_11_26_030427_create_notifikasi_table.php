<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            // PERBAIKAN: user_id merujuk ke tabel users
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('transaksi_id')
                ->nullable()
                ->constrained('transaksi_material')
                ->nullOnDelete();

            $table->enum('tipe', ['status','stok','sistem'])->default('status');
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
}