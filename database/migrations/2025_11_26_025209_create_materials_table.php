<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            // Perbaikan: kode_material wajib dan unik
            $table->string('kode_material')->unique();

            $table->string('nama_material');
            $table->string('satuan')->nullable();

            $table->integer('stok_awal')->default(0);
            $table->integer('min_stok')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
