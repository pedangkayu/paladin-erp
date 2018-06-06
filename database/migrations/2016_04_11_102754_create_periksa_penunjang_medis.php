<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriksaPenunjangMedis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
         Schema::create('periksa_penunjang_medis', function (Blueprint $table) {
            $table->increments('id_pemeriksaan');
           $table->string('id_pemeriksaan_pm_hc');
           $table->string('tgl_periksa_pm');
            $table->string('id_pasien_hc');
            $table->string('id_alasan_periksa');
           $table->string('id_status_jenis_kedatangan');
            $table->string('id_pgw');
            $table->string('id_layanan_rs');
           $table->string('perkiraan_selesai_pm');
            $table->string('tgl_selesai_pm');
            $table->string('proses_pm');
            $table->string('on_hold_pm');
            $table->string('finish_pm');
            $table->string('status_report_pm');
           $table->string('pembatalan_pm');
            $table->string('tidakhadir_pm');
            $table->string('no_antrian_pm');
            $table->string('keterangan_pm');
            $table->string('jam_pm');
            $table->string('id_slot_pm');
            $table->string('konfirmasi_pm');
            $table->string('jam_datang');
            $table->string('kiriman');
            $table->string('usg');
            $table->string('mm');
            $table->string('thx');
            $table->string('lp');
            $table->string('pt');
            $table->string('fna');
            $table->string('pa');
            $table->string('promo');
            $table->string('sms');
            $table->string('note');
            $table->string('baca');
            $table->string('jam_masuk');
           
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
           Schema::drop('periksa_penunjang_medis');
    }
}
