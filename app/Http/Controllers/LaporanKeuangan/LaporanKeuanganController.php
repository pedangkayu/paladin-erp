<?php

namespace App\Http\Controllers\LaporanKeuangan;

use Illuminate\Http\Request;

use App\Models\ref_coa;
use App\Models\ref_report;
use App\Models\data_jurnal;

use App\Models\Views\view_roa_pertahun;
use App\Models\Views\view_dashboard_keuangan;
use App\Models\Views\view_rugi_laba_tahunan;
use App\Models\Views\view_rugi_laba_perbulan;
use App\Models\Views\view_rugi_laba_perhari;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LaporanKeuanganController extends Controller {

	public function getNeraca(Request $req){

		$data['harta'] =  ref_coa::kategori(1, $req->all())->get();
		$data['kewajiban'] =  ref_coa::kategori(2, $req->all())->get();
		$data['modal'] =  ref_coa::kategori(3, $req->all())->get();

		$data['header'] = 'Neraca';
		$data['req'] = $req->all();
		$data['print'] = '/lapkeuangan/printneraca?dari=' . $req->dari . '&sampai=' . $req->sampai;
		return view('LaporanKeuangan.Neraca', $data);
	}
	public function getPrintneraca(Request $req){
		$data['harta'] =  ref_coa::kategori(1, $req->all())->get();
		$data['kewajiban'] =  ref_coa::kategori(2, $req->all())->get();
		$data['modal'] =  ref_coa::kategori(3, $req->all())->get();

		$data['header'] = 'Neraca';
		$data['req'] = $req->all();
		return view('LaporanKeuangan.Print.Neraca', $data);
	}



	public function getRugilaba(Request $req){

		$data['pendapatan'] =  ref_coa::kategori(4, $req->all())->get();
		$data['biaya'] =  ref_coa::kategori(5, $req->all())->get();
		$data['pendapatan_luar_usaha'] =  ref_coa::kategori(6, $req->all())->get();
		$data['biaya_luar_usaha'] =  ref_coa::kategori(7, $req->all())->get();
		$data['pajak'] =  ref_coa::kategori(8, $req->all())->get();

		$data['header'] = 'Rugi Laba';
		$data['req'] = $req->all();
		$data['print'] = '/lapkeuangan/printrugilaba?dari=' . $req->dari . '&sampai=' . $req->sampai;
		return view('LaporanKeuangan.RugiLaba', $data);
	}
	public function getPrintrugilaba(Request $req){

		$data['pendapatan'] =  ref_coa::kategori(4, $req->all())->get();
		$data['biaya'] =  ref_coa::kategori(5, $req->all())->get();
		$data['pendapatan_luar_usaha'] =  ref_coa::kategori(6, $req->all())->get();
		$data['biaya_luar_usaha'] =  ref_coa::kategori(7, $req->all())->get();
		$data['pajak'] =  ref_coa::kategori(8, $req->all())->get();

		$data['header'] = 'Rugi Laba';
		$data['req'] = $req->all();
		return view('LaporanKeuangan.Print.RugiLaba', $data);
	}



	public function getAruskas(Request $req){

		$dari = $req->dari;
		$sampai =  $req->sampai;

		// A. ARUS KAS DARI AKTIVITAS
		$laba_rugi_setelah_pajak = view_rugi_laba_perhari::wherebetween('tanggal', [$dari, $sampai])->get();
		$total_laba_rugi_setelah_pajak = 0;
		foreach ($laba_rugi_setelah_pajak as $lr) {
			$total_laba_rugi_setelah_pajak += ($lr->rugi_laba - $lr->rugi_laba_luar_usaha - $lr->pajak);
		}
		$data['laba_rugi_setelah_pajak'] = $total_laba_rugi_setelah_pajak;
		$data['penyusutan'] = 0;

		$piutang_usaha = ref_coa::aruskas('1.1.3.', $req->all())->first();
		$data['piutang_usaha'] = $piutang_usaha->debit - $piutang_usaha->kredit;

		$persediaan = ref_coa::aruskas('1.1.9.', $req->all())->first();
		$data['persediaan'] = $persediaan->debit - $persediaan->kredit;

		$hutang_usaha = ref_coa::aruskas('2.1.1.', $req->all())->first();
		$data['hutang_usaha'] = $hutang_usaha->kredit - $hutang_usaha->debit;

		$hutang_pemegang_saham = ref_coa::aruskas('2.1.7.', $req->all())->first();
		$data['hutang_pemegang_saham'] = $hutang_pemegang_saham->kredit - $hutang_pemegang_saham->debit;

		$hutang_pajak = ref_coa::aruskas('2.1.5.', $req->all())->first();
		$data['hutang_pajak'] = $hutang_pajak->kredit - $hutang_pajak->debit;

		// B. ARUS KAS DARI AKTIVITAS INVESTASI
		$tanah = ref_coa::aruskas('1.2.2.1.', $req->all())->first();
		$data['tanah'] = $tanah->debut - $tanah->kredit;

		$bangunan = ref_coa::aruskas('1.2.2.2.', $req->all())->first();
		$data['bangunan'] = $bangunan->debut - $bangunan->kredit;

		$kendaraan = ref_coa::aruskas('1.2.2.5.', $req->all())->first();
		$data['kendaraan'] = $kendaraan->debut - $kendaraan->kredit;

		$inventaris_kantor = ref_coa::aruskas('1.2.2.4.', $req->all())->first();
		$data['inventaris_kantor'] = $inventaris_kantor->debut - $inventaris_kantor->kredit;

		$inventaris_medis = ref_coa::aruskas('1.2.2.3.', $req->all())->first();
		$data['inventaris_medis'] = $inventaris_medis->debut - $inventaris_medis->kredit;

		$data['header'] = 'Arus Kas';
		$data['req'] = $req->all();
		$data['print'] = '/lapkeuangan/printaruskas?dari=' . $req->dari . '&sampai=' . $req->sampai;
		return view('LaporanKeuangan.ArusKas', $data);
	}

	public function getPrintaruskas(Request $req){

		$dari = $req->dari;
		$sampai =  $req->sampai;

		// A. ARUS KAS DARI AKTIVITAS
		$laba_rugi_setelah_pajak = view_rugi_laba_perhari::wherebetween('tanggal', [$dari, $sampai])->get();
		$total_laba_rugi_setelah_pajak = 0;
		foreach ($laba_rugi_setelah_pajak as $lr) {
			$total_laba_rugi_setelah_pajak += ($lr->rugi_laba - $lr->rugi_laba_luar_usaha - $lr->pajak);
		}
		$data['laba_rugi_setelah_pajak'] = $total_laba_rugi_setelah_pajak;
		$data['penyusutan'] = 0;

		$piutang_usaha = ref_coa::aruskas('1.1.3.', $req->all())->first();
		$data['piutang_usaha'] = $piutang_usaha->debit - $piutang_usaha->kredit;

		$persediaan = ref_coa::aruskas('1.1.9.', $req->all())->first();
		$data['persediaan'] = $persediaan->debit - $persediaan->kredit;

		$hutang_usaha = ref_coa::aruskas('2.1.1.', $req->all())->first();
		$data['hutang_usaha'] = $hutang_usaha->kredit - $hutang_usaha->debit;

		$hutang_pemegang_saham = ref_coa::aruskas('2.1.7.', $req->all())->first();
		$data['hutang_pemegang_saham'] = $hutang_pemegang_saham->kredit - $hutang_pemegang_saham->debit;

		$hutang_pajak = ref_coa::aruskas('2.1.5.', $req->all())->first();
		$data['hutang_pajak'] = $hutang_pajak->kredit - $hutang_pajak->debit;

		// B. ARUS KAS DARI AKTIVITAS INVESTASI
		$tanah = ref_coa::aruskas('1.2.2.1.', $req->all())->first();
		$data['tanah'] = $tanah->debut - $tanah->kredit;

		$bangunan = ref_coa::aruskas('1.2.2.2.', $req->all())->first();
		$data['bangunan'] = $bangunan->debut - $bangunan->kredit;

		$kendaraan = ref_coa::aruskas('1.2.2.5.', $req->all())->first();
		$data['kendaraan'] = $kendaraan->debut - $kendaraan->kredit;

		$inventaris_kantor = ref_coa::aruskas('1.2.2.4.', $req->all())->first();
		$data['inventaris_kantor'] = $inventaris_kantor->debut - $inventaris_kantor->kredit;

		$inventaris_medis = ref_coa::aruskas('1.2.2.3.', $req->all())->first();
		$data['inventaris_medis'] = $inventaris_medis->debut - $inventaris_medis->kredit;



		$data['header'] = 'Arus Kas';
		$data['req'] = $req->all();
		return view('LaporanKeuangan.Print.ArusKas', $data);
	}


	public function getBukubesar(Request $req){
		$ledgers = [];

		// COA
		$coas = [];
		$ref_coa = ref_coa::orderby('seri', 'asc');
		foreach($ref_coa->get() as $coa){
			$coas[$coa->parent_id][] = $coa;
		}

		$seri_dari = $req->exists('coa_dari') ? $req->coa_dari : 1;
		$seri_sampai = $req->exists('coa_sampai') ? $req->coa_sampai : $ref_coa->count();

		$params = [
			'dari' => $req->exists('dari') ? $req->dari : date('Y-m-d', strtotime('-1 Month', time())),
			'sampai' => $req->exists('sampai') ? $req->sampai : date('Y-m-d'),
			'coa_dari' => $seri_dari,
			'coa_sampai' => $seri_sampai,
			'all' => $req->exists('all') ? true : false
		];

		//dd($params);

		$ledgs = data_jurnal::bukubesar($params)->get();
		$ids = [];
		foreach($ledgs as $lg){
			$ledgers[$lg->id_coa][] = $lg;
			$ids[] = $lg->id_coa;
		}


		$data['leadgers'] = $ledgers;
		$data['print'] = str_replace('bukubesar', 'printbukubesar', $req->fullurl());
		$data['all'] = $req->exists('all') ? true : false;

		$data['coa_dari'] = \Format::select_coa_ledger($coas, 0, $seri_dari);
		$data['coa_sampai'] = \Format::select_coa_ledger($coas,0 , $seri_sampai);
		$data['coas'] = ref_coa::where('grup', 2)->orderby('seri', 'asc')->get();
		$data['ids'] = $ids;

		return view('LaporanKeuangan.BukuBesar', $data);
	}


	public function getPrintbukubesar(Request $req){
		$ledgers = [];

		// COA
		$coas = [];
		$ref_coa = ref_coa::orderby('kode', 'asc');
		foreach($ref_coa->get() as $coa){
			$coas[$coa->parent_id][] = $coa;
		}

		$seri_dari = $req->exists('coa_dari') ? $req->coa_dari : 1;
		$seri_sampai = $req->exists('coa_sampai') ? $req->coa_sampai : $ref_coa->count();

		$params = [
			'dari' => $req->exists('dari') ? $req->dari : date('Y-m-d', strtotime('-1 Month', time())),
			'sampai' => $req->exists('sampai') ? $req->sampai : date('Y-m-d'),
			'coa_dari' => $seri_dari,
			'coa_sampai' => $seri_sampai,
			'all' => $req->exists('all') ? true : false
		];



		$ledgs = data_jurnal::bukubesar($params)->get();
		$ids = [];
		foreach($ledgs as $lg){
			$ledgers[$lg->id_coa][] = $lg;
			$ids[] = $lg->id_coa;
		}


		$data['leadgers'] = $ledgers;
		$data['print'] = str_replace('bukubesar', 'printbukubesar', $req->fullurl());
		$data['all'] = $req->exists('all') ? true : false;

		$data['coa_dari'] = \Format::select_coa_ledger($coas, 0, $seri_dari);
		$data['coa_sampai'] = \Format::select_coa_ledger($coas,0 , $seri_sampai);
		$data['coas'] = ref_coa::where('grup', 2)->orderby('kode', 'asc')->get();
		$data['ids'] = $ids;
		$data['req'] = $params;


		return view('LaporanKeuangan.Print.BukuBesar', $data);
	}


	public function getNeracasaldo(Request $req){
		$ids = [];
		$content = [];
		$content1 = [];
		$content2 = [];

		$params = [
			'dari' => $req->exists('dari') ? $req->dari : date('Y-m-d', strtotime('-1 Month', time())),
			'sampai' => $req->exists('sampai') ? $req->sampai : date('Y-m-d')
		];

		$ns = data_jurnal::neracasaldo($params)->get();
		foreach($ns as $n){
			$ids[] = $n->id_coa;
			$content1[] = $n;
		}

		if(!$req->exists('ada')):
		$coas = ref_coa::neracasaldo($ids)->get();
		foreach($coas as $coa){
			$content2[] = $coa;
		}
		endif;

		$content = array_merge($content1, $content2);

		usort($content, function($a, $b){
			return $a['seri'] - $b['seri'];
		});

		$data['ns'] = $content;
		$data['print'] = str_replace('neracasaldo', 'printneracasaldo', $req->fullurl());
		$data['ada'] = $req->exists('ada') ? true : false;

		return view('LaporanKeuangan.NeracaSaldo', $data);

	}


	public function getPrintneracasaldo(Request $req){
		$ids = [];
		$content = [];
		$content1 = [];
		$content2 = [];

		$params = [
			'dari' => $req->exists('dari') ? $req->dari : date('Y-m-d', strtotime('-1 Month', time())),
			'sampai' => $req->exists('sampai') ? $req->sampai : date('Y-m-d')
		];

		$ns = data_jurnal::neracasaldo($params)->get();
		foreach($ns as $n){
			$ids[] = $n->id_coa;
			$content1[] = $n;
		}

		if(!$req->exists('ada')):
		$coas = ref_coa::neracasaldo($ids)->get();
		foreach($coas as $coa){
			$content2[] = $coa;
		}
		endif;

		$content = array_merge($content1, $content2);

		usort($content, function($a, $b){
			return $a['seri'] - $b['seri'];
		});

		$data['ns'] = $content;
		$data['req'] = $params;

		return view('LaporanKeuangan.Print.NeracaSaldo', $data);

	}

	public function getDashboard(){

		return view('LaporanKeuangan.Dashboard');
	}

}
