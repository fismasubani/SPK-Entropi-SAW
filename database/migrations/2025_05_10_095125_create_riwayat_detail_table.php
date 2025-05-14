<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatDetailTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riwayat_id')->constrained('riwayat_perhitungan')->onDelete('cascade');
            $table->string('nama_alternatif');
            $table->json('nilai_kriteria_json');
            $table->float('skor_akhir');
            $table->unsignedInteger('peringkat');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_detail');
    }
}
