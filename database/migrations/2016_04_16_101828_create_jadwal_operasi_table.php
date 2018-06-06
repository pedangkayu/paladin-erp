<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalOperasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_operasi', function (Blueprint $table) {
                 $table->increments('id_jadwal_operasi');
                 $table->string('id_jadwal_operasi_hc');
                 $table->string('tanggal_jadwal_operasi');
                 $table->string('tanggal_operasi');
                 $table->string('id_status_jenis_kedatangan');
                 $table->string('id_layanan_rs');
                 $table->string('id_master_ruang_operasi');
                 $table->string('id_pasien');
                 $table->string('jam_operasi');
                 $table->string('ketrangan_jadwal_operasi');
                 $table->string('surat_pembiusan_operasi');
                 $table->string('konfirmasi_jadwal_operasi');
                 $table->string('proses_jadwal_operasi');
                 $table->string('on_hold_jadwal_operasi');
                 $table->string('finish_jadwal_operasi');
                 $table->string('diagnosa_jadwal_operasi');
                 $table->string('status_jadwal');
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
        Schema::drop('jadwal_operasi');
    }
    
}
