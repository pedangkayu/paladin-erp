<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefServiceDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('ref_service_detail', function (Blueprint $table) {
            $table->increments('id_srvice_detail');
            $table->string('id_unit');
            $table->string('kebutuhan');
        
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::drop('ref_service_detail');
    }
}
