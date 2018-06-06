<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\ref_bank;
use App\Models\data_vendor;
use App\Models\ref_asuransi;
use App\Models\data_feedback;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller{
 	public function postStatus(Request $req){
 		if($req->ajax()){
 			User::find(\Auth::user()->id_user)->update([
 				'status_user' => $req->mystatus
 			]);
 		}
 	}

 	public function getFeedback(Request $req){
 		if($req->ajax()){
 			$total = data_feedback::notif()->first();
 			$res = !empty($total->total) && $total->total > 9 ? '9+' : empty($total->total) ? 0 : $total->total;
 			return json_encode([
 				'total' => $res
 			]);
 		}
 	} 

 	public function getVendors(Request $req){
 		if($req->ajax()){
 			$res = [];
 			$out = '<option value="">-Pilih Supplier-</option>';
 			$items = data_vendor::where('status', 1)->get();
 			foreach($items as $item){
 				$select = $req->select == $item->id_vendor ? 'selected="selected"' : '';
 				$out .= '<option value="' . $item->id_vendor . '" ' . $select . '>' . $item->nm_vendor . '</option>';
 			}
 			$res['content'] = $out;
 			return json_encode($res);
 		}
 	}


 	public function getPaymentmethod(Request $req){
 		if($req->ajax()){

 			$res = [];
 			$out = '';

 			if($req->tipe == 1){
 				foreach(ref_bank::all() as $val){
 					$out .= '<option value="' . $val->id_bank . '">' . $val->nm_bank . '</ption>';
 				}
 			}else{
 				foreach(ref_asuransi::all() as $val){
 					$out .= '<option value="' . $val->id_asuransi . '">' . $val->nm_asuransi . '</ption>';
 				}
 			}

 			$res['content'] = $out;
 			return response()->json($res);

 		}
 	}
 	public function getPaymentmethoddeposit(Request $req){
 		if($req->ajax()){

 			$res = [];
 			$out = '';

 			if($req->tipe == 1){
 				foreach(ref_bank::all() as $val){
 					$out .= '<option value="' . $val->id_bank . '">' . $val->nm_bank . '</ption>';
 				}
 			}else{
 				
 					$out .= '<input type="hidden" value="0" name="method">';
 				
 			}

 			$res['content'] = $out;
 			return response()->json($res);

 		}
 	}

}
