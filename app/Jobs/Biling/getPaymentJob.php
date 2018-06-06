<?php

namespace App\Jobs\Biling;

use App\Models\ref_coa;
use App\Models\data_faktur;

use App\Models\data_jurnal;
use App\Models\data_pasien;
use App\Models\data_voucer_jurnal;
use App\Models\data_config_coa_biling as Config;
use App\Models\data_config_coa_deposit as ConfigDeposit;
use App\Models\ref_payment_method_item;

use App\Models\data_deposit;
use App\Models\data_log_deposit;

use App\Models\data_jurnal_pembayaran;

use App\Models\data_piutang_pasien;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class getPaymentJob extends Job implements SelfHandling {


    public $req;

    public function __construct(array $req) {
        $this->req = $req;
    }


    public function handle() {

        $me = \Me::data()->id_karyawan;
        $config =  Config::active()->first();
        $configDeposit =  configDeposit::active()->first();
        $status = 0;
        //dd($this->req);

        $grandtotal = 0;

        try {

            if(!empty($this->req['dgn_saldo'])){
                if($this->req['saldo'] > $this->req['saldo_akhir'])
                    throw new \Exception("Maaf, Saldo yang anda masukan melebihi saldo yang dimiliki !", 1);
            }

            \DB::begintransaction();

            $faktur = data_faktur::find($this->req['id_faktur']);

            $voucer = data_voucer_jurnal::create([
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'keterangan' => $this->req['keterangan']
            ]);

            // Jurnal saldo
            if(!empty($this->req['dgn_saldo'])){
                $coa = ref_coa::find($config->coa_saldo);
                data_jurnal::create([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_saldo,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'Pembayaran Faktur dengan Saldo',
                    'id_payment_methode' => 1,
                    'debit' => $this->req['saldo'],
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 18,
                    'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);


                // Data pembayaran dengan saldo
                data_jurnal_pembayaran::create([
                    'id_payment_method_item' => 1,
                    'id_faktur' => $faktur->id_faktur,
                    'id_asuransi' => 0,
                    'no_asuransi' => 0,
                    'id_pasien' => $this->req['id_pasien'],
                    'id_bank' => 0,
                    'tipe_payment_method' => 6, // Deposit
                    'jumlah' => $this->req['saldo'],
                    'id_karyawan' => $me
                ]);

                $grandtotal = $this->req['saldo'];
            }

            // Payment method
            if(!empty($this->req['id_payment_method'])):
                foreach($this->req['id_payment_method'] as $i => $id_payment_method){

                    $pi = ref_payment_method_item::find($this->req['id_payment_method_item'][$i]);

                    // Input tipe payment method
                    $coa = ref_coa::find($pi->id_coa);
                    data_jurnal::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_coa' =>  $pi->id_coa,
                        'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                        'deskripsi' => 'Pembayaran melalui ' . $coa->nm_coa,
                        'id_payment_methode' => $pi->id_payment_method,
                        'debit' => $this->req['total_payment'][$i],
                        'kredit' => 0,
                        'id_karyawan' => $me,
                        'tipe_jurnal' => 19,
                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                        'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                    ]);

                    $grandtotal += $this->req['total_payment'][$i];

                    $id_bank = $pi->tipe_payment_method == 2 ? $pi->id_option : 0;
                    $id_asuransi = $pi->tipe_payment_method == 3 ? $pi->id_option : 0;

                    // Data pembayaran
                    data_jurnal_pembayaran::create([
                        'id_payment_method_item' => $this->req['id_payment_method_item'][$i],
                        'id_faktur' => $faktur->id_faktur,
                        'id_asuransi' => $id_asuransi,
                        'no_asuransi' => $this->req['referensi'][$i],
                        'id_pasien' => $this->req['id_pasien'],
                        'id_bank' => $id_bank,
                        'tipe_payment_method' => $pi->tipe_payment_method,
                        'jumlah' => $this->req['total_payment'][$i],
                        'id_karyawan' => $me
                    ]);
                }
            endif;

            if(empty($grandtotal))
                throw new \Exception("Maaf tidak ada jumlah yang ditemukan, silakan masukan jumlah pembayaran yang akan dibayarkan", 1);

            if($grandtotal > $this->req['total'])
                throw new \Exception("Jumlah yang anda bayarkan terlalu besar", 1);

            // Jurnal pembayaran
            $coa = ref_coa::find($config->coa_sebelum_dibayar);
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $config->coa_sebelum_dibayar,
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => 'Pembayaran faktur #' . $faktur->nomor_faktur,
                'id_payment_methode' => 0,
                'debit' => 0,
                'kredit' => $grandtotal,
                'id_karyawan' => $me,
                'tipe_jurnal' => 20,
                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);

            $amount_due = $faktur->amount_due;

            $status = ($amount_due + $grandtotal) >= $this->req['total'] ? 2 : 1;

            $faktur->amount_due = $amount_due + $grandtotal;
            $faktur->status = $status;
            $faktur->save();


            $piutang = data_piutang_pasien::where('id_faktur', $faktur->id_faktur)->where('id_pasien', $this->req['id_pasien'])->first();
            if($piutang != null){
                $sisa = $piutang->total - $grandtotal;
                $piutang->update([
                    'total' => $sisa,
                    'status' => empty($sisa) ? 2 : 1
                ]);
            }


            // Pengurangan saldo
            if(!empty($this->req['dgn_saldo'])){
                data_log_deposit::create([
                    'id_pasien' => $this->req['id_pasien'],
                    'id_deposit' => $this->req['id_deposit'],
                    'keterangan' => 'Pembayaran faktur #' . $faktur->nomor_faktur,
                    'id_bank' => 0,
                    'id_payment_method' => 3, // Pemotongan deposit
                    'id_karyawan' => $me,
                    'masuk' => 0,
                    'keluar' => $this->req['saldo']
                ]);

                if($this->req['id_deposit'] > 0):
                    $dep = data_deposit::find($this->req['id_deposit']);
                    $sisa = $dep->saldo;
                    $dep->saldo = $sisa - $this->req['saldo'];
                    $dep->save();
                endif;


                $pasien = data_pasien::where('id_pasien_hc', $this->req['id_pasien'])->first();

                // Mutasi jurnal saldo
                // $voucer = data_voucer_jurnal::create([
                //     'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                //     'keterangan' => 'Mutasi deposit. Pasien ID #' . $pasien->id_pasien_hc . ' pembayaran Faktur #' . $faktur->nomor_faktur
                // ]);

                // // Pembayaran dengan saldo Kas / Bank
                // $co = ref_coa::find($configDeposit->coa_pembayaran_cash);
                // data_jurnal::create([
                //     'id_faktur' => 0,
                //     'id_coa' =>  $config->coa_saldo,
                //     'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                //     'deskripsi' => 'Pembayaran faktur #' . $faktur->nomor_faktur . ' dengan saldo',
                //     'id_payment_methode' => 0,
                //     'debit' => $this->req['saldo'],
                //     'kredit' => 0,
                //     'id_karyawan' => $me,
                //     'tipe_jurnal' => 23,
                //     'id_option' => $dep->id_deposit,
                //     'link_slug' => '/Deposit/transaksi/' . $dep->id_deposit,
                //     'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                // ]);


                // // PENDPATAN DIBAYAR DIMUKA
                // $co = ref_coa::find($configDeposit->coa_deposit);
                // data_jurnal::create([
                //     'id_faktur' => 0,
                //     'id_coa' =>  $configDeposit->coa_deposit,
                //     'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                //     'deskripsi' => 'Pembayaran faktur #' . $faktur->nomor_faktur . ' dengan saldo',
                //     'id_payment_methode' => 0,
                //     'debit' => 0,
                //     'kredit' => $this->req['saldo'],
                //     'id_karyawan' => $me,
                //     'tipe_jurnal' => 24,
                //     'id_option' => $dep->id_deposit,
                //     'link_slug' => '/Deposit/transaksi/' . $dep->id_deposit,
                //     'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                // ]);

            }


            // jika menggunakan saldo ada pengurangan saldo untuk pasien

            \DB::commit();
            return [
                'label' => 'success',
                'err' => 'Pembayaran berhasil !'
            ];

        } catch (\Exception $e) {
            \DB::rollback();

            return [
                'label' => 'danger',
                'err' => $e->getMessage()
            ];
        }

    }
}
