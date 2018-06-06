<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelayananRawatInap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('pelayanan_rawat_inap', function (Blueprint $table) {
                $table->increments('id_pelayana_ri');
                $table->string('id_antrian_rwt_inap');
                $table->string('tgl_pendaftaran_rwt_inap');
                $table->string('id_rs');
                $table->string('id_pgw');
                $table->string('id_status_jenis_kedatangan');
                $table->string('id_pasien');
                $table->string('id_layanan_rs');
                $table->string('rencana_mrs');
                $table->string('rencana_krs');
                $table->string('keterangan_rwt_inap');
                $table->string('status_masuk_rwt_inap');
                $table->string('status_keluar_rwt_inap');
                $table->string('ket_mrs');
                $table->timestamps();

        });
    }

  
    public function down()
    {
          Schema::drop('pelayanan_rawat_inap');
    }
}
