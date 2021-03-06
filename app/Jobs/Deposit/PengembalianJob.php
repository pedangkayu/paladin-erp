<?php

namespace App\Jobs\Deposit;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_deposit;
use App\Models\data_log_deposit;
use App\Models\data_pasien;


use App\Models\data_voucer_jurnal;
use App\Models\data_jurnal;
use App\Models\ref_coa;
use App\Models\ref_bank;
use App\Models\data_config_coa_deposit as Config;

class PengembalianJob extends Job implements SelfHandling
{
    public $req;

    public function __construct(array $req){
       $this->req = $req;
    }

    public function handle()
    {
         $me = \Me::data()->id_karyawan;
         $config = Config::active()->first();
       // dd($this->req);
         try{
            \DB::begintransaction();
            $depo = data_deposit::find($this->req['id_deposit']);
            $depo->update([
                    'id_pasien'   =>$this->req['id_pasien_hc'],
                    'saldo'       =>$this->req['sisa'] - $this->req['keluar'],
                    'id_karyawan' =>$me,
                    'catatan'     => 'saldo deposit',
                    'tanggal'     => date('Y-m-d', strtotime($this->req['datetime_in'])),
                ]);


            $create=data_log_deposit::create([
                    'id_pasien'         =>$this->req['id_pasien_hc'],
                    'id_payment_method' =>$this->req['tipe'],
                    'id_deposit'        =>$this->req['id_deposit'],
                    'id_bank'           =>$this->req['method'],
                    'keterangan'        =>'Pengembalian Saldo',
                    'masuk'             =>$this->req['masuk'],
                    'id_karyawan'       =>$me,
                    'keluar'            =>$this->req['keluar'],
                    'catatan'           =>$this->req['keterangan'],
                ]);




                // input Jurnal
                $pasien = data_pasien::where('id_pasien_hc', $this->req['id_pasien_hc'])->first();
                $voucer = data_voucer_jurnal::create([
                    'tanggal' => date('Y-m-d', strtotime($this->req['datetime_in'])),
                    'keterangan' => 'Penarikan deposit. ID #' . $pasien->id_pasien_hc . ', ' . $pasien->nama_pasien
                ]);

                $coa_bank = 0;
                if($this->req['tipe'] == 1){
                    $bank = ref_bank::find($this->req['method']);
                    $coa_bank = $bank->id_coa;
                }
                $id_coa = $this->req['tipe'] == 3 ? $config->coa_pembayaran_cash : $coa_bank;
                
                // Kas / Bank
                $co = ref_coa::find($id_coa);
                data_jurnal::create([
                    'id_faktur' => 0,
                    'id_coa' =>  $id_coa,
                    'tanggal' => date('Y-m-d', strtotime($this->req['datetime_in'])),
                    'deskripsi' => $co->nm_coa,
                    'id_payment_methode' => 0,
                    'debit' => 0,
                    'kredit' => $this->req['keluar'],
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 23,
                    'id_option' => $depo->id_deposit,
                    'link_slug' => '/Deposit/transaksi/' . $depo->id_deposit,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);


                // PENDPATAN DIBAYAR DIMUKA
                $co = ref_coa::find($config->coa_deposit);
                data_jurnal::create([
                    'id_faktur' => 0,
                    'id_coa' =>  $config->coa_deposit,
                    'tanggal' => date('Y-m-d', strtotime($this->req['datetime_in'])),
                    'deskripsi' => $co->nm_coa,
                    'id_payment_methode' => 0,
                    'debit' => $this->req['keluar'],
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 24,
                    'id_option' => $depo->id_deposit,
                    'link_slug' => '/Deposit/transaksi/' . $depo->id_deposit,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);

             \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Pengembalian Saldo Berhasil sebesar.  Rp.'.$this->req['keluar'] 
            ];

        }catch(\Exception $e){

            \DB::rollback();
            return [
               'res' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];

        }
    }
}
