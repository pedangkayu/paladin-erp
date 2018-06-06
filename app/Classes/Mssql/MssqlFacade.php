<?php

	namespace App\Classes\Mssql;
	use Illuminate\Support\Facades\Facade;

	class MssqlFacade extends Facade {
	    
	    protected static function getFacadeAccessor() { return 'MSSQL'; }

	}