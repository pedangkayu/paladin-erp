<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


// Widget Keuangan
use App\Models\Views\view_roa_pertahun;
use App\Models\Views\view_dashboard_keuangan;
use App\Models\Views\view_rugi_laba_tahunan;
use App\Models\Views\view_rugi_laba_perbulan;
use App\Models\Views\view_rugi_laba_perhari;

class DashboardController extends Controller {

  public function getKeuangan(Request $req){

    if($req->ajax()){
			$res = [];
			$top = view_dashboard_keuangan::first();

			$dari 	= date('Y') - 5;
			$sampai = date('Y');

			$roas_arr = [];
			$roas 	= view_roa_pertahun::wherebetween('tahun', [$dari, $sampai])->get();
			foreach($roas as $roa){
				$roas_arr[] = [
					'tahun' => $roa->tahun,
					'roa' => empty($roa->roa) ? 0 : $roa->roa
				];
			}

			$rls_arr = [];
			$rls 	= view_rugi_laba_tahunan::wherebetween('tahun', [$dari, $sampai])->get();
			foreach($rls as $rl){
				$rls_arr[] = [
					'tahun' => $rl->tahun,
					'rugi_laba' => empty($rl->rugi_laba) ? 0 : $rl->rugi_laba
				];
			}

			$rlbln_arr = [];
			$rlsbln 	= view_rugi_laba_perbulan::where('tahun', date('Y'))->get();
			foreach($rlsbln as $rl){
				$rlbln_arr[$rl->bulan] = empty($rl->rugi_laba) ? 0 : $rl->rugi_laba;
			}

			$rugilababln = [];
			for($i=1; $i < 13; $i++){
				$rugilababln[] = [
					'bulan' => \Format::nama_bulan_alias($i),
					'rugi_laba' => empty($rlbln_arr[$i]) ? 0 : $rlbln_arr[$i]
				];
			}

			$res['roas'] = $roas_arr;
			$res['rugilaba'] = $rls_arr;
			$res['rugilababln'] = $rugilababln;
			$res['res'] = $top;
			return response()->json($res);
		}

  }

}
