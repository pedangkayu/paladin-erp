<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntrianNonPoliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('antrian_non_poli', function (Blueprint $table) {
                          $table->increments('id_antrian_poli');
                          $table->string('no_antrian_non_poli_hc');
                          $table->string('tgl_non_poli');
                          $table->string('id_pgw');
                          $table->string('peg_idpgw');
                          $table->string('id_alasan_periksa');
                          $table->string('id_layanan_rs');
                          $table->string('id_pasien');
                          $table->string('id_status_jenis_kedatangan');
                          $table->string('alasan_periksa_non_poli');
                          $table->string(' proses_non_poli');
                          $table->string(' on_hold_non_poli');
                          $table->string('finish_non_poli');
                          $table->string('status_report_hc');
                          $table->string('tidak_hadir_non_poli');
                          $table->string('pembatalan_non_poli');
                          $table->string('diagnosa_hc');
                          $table->string('masalah_hc');
                          $table->string(' waktu_hc');
                          $table->string('keterangan_np');
                          $table->string('jam_np');
                          $table->string('id_slot_np');
                          $table->string('jam_konfirmasi');
                          $table->string('jam_masuk');
                          $table->string('flag_mr');
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
      Schema::drop('antrian_non_poli');
    }
}
