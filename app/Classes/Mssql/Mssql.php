<?php
	namespace App\Classes\Mssql;

	use DB;

	class Mssql {

		protected $driver = 'sqlsrv';

		/* 
		* Doc https://laravel.com/docs/5.1/queries
		*/
		
		public function tbl($table){
			return DB::connection($this->driver)->table($table);
		}

	}