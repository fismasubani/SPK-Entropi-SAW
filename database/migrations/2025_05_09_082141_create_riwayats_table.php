<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayats', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_perhitungan');
            $table->json('hasil_preferensi');
            $table->json('total_preferensi'); // baru ditambahkan
            $table->json('peringkat');
            $table->unsignedInteger('jumlah_alternatif');
            $table->json('data_kriteria');
            $table->json('data_alternatif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayats');
    }
}
