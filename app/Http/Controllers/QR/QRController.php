<?php

namespace App\Http\Controllers\QR;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QRController extends Controller {

    public function index(){

		return view('QR.QRReader');
	}


	public function res(Request $req){
		return json_encode([
			'result' => $req->data
		]);
	}
}
