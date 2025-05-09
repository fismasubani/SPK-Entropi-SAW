<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alternatif_id');
            $table->unsignedBigInteger('crips_id');
            $table->timestamps();

            // Tambahkan foreign key dengan cascade delete
            $table  ->foreign('alternatif_id')
                    ->references('id')->on('alternatifs')
                    ->onDelete('cascade');
            $table  ->foreign('crips_id')
                    ->references('id')->on('crips')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penilaians');
    }
}
