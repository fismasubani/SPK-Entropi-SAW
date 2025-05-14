<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPerhitunganTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_perhitungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perhitungan')->nullable();
            $table->dateTime('tanggal_perhitungan');
            $table->unsignedInteger('jumlah_alternatif');
            $table->json('kriteria_json');
            $table->enum('metode', ['saw', 'entropi-saw']);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_perhitungan');
    }
}