<?php
	
	namespace App\Classes\LogUser;

	use App\Models\data_aktivitas;

	class LogUser{

		public function create($text){
			$me = \Me::data();
			if(!empty($me->id_karyawan))
				return data_aktivitas::create([
					'id_karyawan' => $me->id_karyawan,
					'keterangan' => $text
				]);
		}

	}