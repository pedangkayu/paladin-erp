<?php

namespace App\Jobs\Biling;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_faktur;
use App\Models\data_faktur_pasien;
use App\Models\data_faktur_pasien_item;

use App\Models\ref_coa;
use App\Models\data_jurnal;
use App\Models\data_voucer_jurnal;
use App\Models\data_config_coa_biling as Config;
use App\Models\data_config_coa_deposit as ConfigDeposit;

use App\Models\data_pasien;
use App\Models\data_deposit;
use App\Models\data_log_deposit;

use App\Models\data_piutang_pasien;


class editBilingJob extends Job implements SelfHandling {

    public $req;

    public function __construct(array $req) {
        $this->req = $req;
    }

    public function handle() {
        
        //dd($this->req);
        $me = \Me::data()->id_karyawan;
        $config =  Config::active()->first();
        $configDeposit =  configDeposit::active()->first();

        try {
            
            \DB::begintransaction();
            $faktur = data_faktur::find($this->req['id_faktur']);
            $amount_due = $faktur->amount_due;
            $status = $faktur->status;

            // adjustment
            if($faktur->adjustment != $this->req['adjustment']):
                $ju = data_jurnal::where('tipe_jurnal', 3)
                    ->where('id_faktur', $faktur->id_faktur)
                    ->update([
                        'tanggal' => date('Y-m-d'),
                        'debit' => $this->req['adjustment'],
                        'kredit' => 0
                    ]);
            endif;


            // Piutang Usaha
            $coa = ref_coa::find($config->coa_sebelum_dibayar);
            $ju = data_jurnal::where('tipe_jurnal', 7)
                ->where('id_faktur', $faktur->id_faktur)
                ->update([
                    'tanggal' => date('Y-m-d'),
                    'debit' => $this->req['grandtotal'],
                    'kredit' => 0,
                ]);

            // Pengembalian Deposit
            //dd( [$this->req['grandtotal'], $faktur->total, $faktur->status]);
            if( ($amount_due > $this->req['grandtotal']) ){

                $selisish = $amount_due - $this->req['grandtotal'];

                $saldo = data_deposit::firstOrCreate([ 'id_pasien' => $faktur->id_pasien]);
                data_log_deposit::create([
                    'id_pasien' => $faktur->id_pasien,
                    'id_deposit' => $saldo->id_deposit,
                    'keterangan' => 'Pembayaran faktur #' . $faktur->nomor_faktur,
                    'id_bank' => 0,
                    'id_payment_method' => 3, // Pemotongan deposit
                    'id_karyawan' => $me,
                    'masuk' => 0,
                    'keluar' => $selisish
                ]);

                
                $sisa = $saldo->saldo;
                $saldo->saldo = $sisa + $selisish;
                $saldo->save();
                
                $pasien = data_pasien::where('id_pasien_hc', $faktur->id_pasien)->first();

                // input Jurnal
                $voucer = data_voucer_jurnal::create([
                    'tanggal' => date('Y-m-d'),
                    'keterangan' => 'Penambahan deposit. Pasien ID #' . $pasien->id_pasien_hc . ' pembayaran Faktur #' . $faktur->nomor_faktur
                ]);

                // Kas / Bank
                $co = ref_coa::find($configDeposit->coa_pembayaran_cash);
                data_jurnal::create([
                    'id_faktur' => 0,
                    'id_coa' =>  $configDeposit->coa_pembayaran_cash,
                    'tanggal' => date('Y-m-d'),
                    'deskripsi' => $co->nm_coa,
                    'id_payment_methode' => 0,
                    'debit' => $selisish,
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 23,
                    'id_option' => $saldo->id_deposit,
                    'link_slug' => '/Deposit/transaksi/' . $saldo->id_deposit,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);


                // PENDPATAN DIBAYAR DIMUKA
                $co = ref_coa::find($configDeposit->coa_deposit);
                data_jurnal::create([
                    'id_faktur' => 0,
                    'id_coa' =>  $configDeposit->coa_deposit,
                    'tanggal' => date('Y-m-d'),
                    'deskripsi' => $co->nm_coa,
                    'id_payment_methode' => 0,
                    'debit' => 0,
                    'kredit' => $selisish,
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 24,
                    'id_option' => $saldo->id_deposit,
                    'link_slug' => '/Deposit/transaksi/' . $saldo->id_deposit,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);

            }


            // data_faktur_pasien
            if(!empty($this->req['id_faktur_pasien'])):
                foreach($this->req['id_faktur_pasien'] as $i => $id_faktur_pasien):
                    $item = data_faktur_pasien::where('id_faktur_pasien', $id_faktur_pasien);
                    $item->update([
                        'qty' => $this->req['qty_faktur_pasien'][$i],
                        'diskon' => empty($this->req['tarif_kamar_faktur_pasien'][$i]) ? $this->req['diskon_faktur_pasien'][$i] : $this->req['diskon_rinap_faktur_pasien'][$i],
                        'harga_jual' => $this->req['biaya_faktur_pasien'][$i],
                        'subtotal' => $this->req['subtotal_faktur_pasien'][$i],

                        'tarif_dasar' => $this->req['tarif_dasar_faktur_pasien'][$i],
                        'total_sewa' => $this->req['total_sewa_faktur_pasien'][$i],
                        'tarif_dasar_rinap' => $this->req['tarif_dasar_rinap_faktur_pasien'][$i],
                        'tarif_kamar' => $this->req['tarif_kamar_faktur_pasien'][$i]
                    ]);


                    // Pendapatan Resep
                    data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_pendapatan'][$i])
                        ->where('id_option', $id_faktur_pasien)
                        ->update([
                            'tanggal' => date('Y-m-d'),
                            'debit' => 0,
                            'kredit' => $this->req['subtotal_faktur_pasien'][$i] > 0 ? $this->req['subtotal_faktur_pasien'][$i] : $this->req['tarif_kamar_faktur_pasien'][$i]
                        ]);


                    if($this->req['subtotal_faktur_pasien'][$i] > 0):
                        // =========================== voucer_persediaan ========================
                        $harga_beli_resep_item = (empty($this->req['harga_beli_item'][$i]) ? $this->req['tarif_dasar_faktur_pasien'][$i] : $this->req['harga_beli_item'][$i]) * $this->req['qty_faktur_pasien'][$i];
                        // Persediaan Baarang
                        data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_persediaan'][$i])
                            ->where('id_option', $id_faktur_pasien)
                            ->update([
                                'tanggal' => date('Y-m-d'),
                                'debit' => 0,
                                'kredit' => $harga_beli_resep_item,
                            ]);


                        // Biaya HPP
                        data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_hpp'][$i])
                            ->where('id_option', $id_faktur_pasien)
                            ->update([
                                'tanggal' => date('Y-m-d'),
                                'debit' => $harga_beli_resep_item,
                                'kredit' => 0,
                            ]);
                        
                    endif;

                endforeach;
            endif;


            // data_faktur_pasien_item
            if(!empty($this->req['id_faktur_pasien_item'])):
                foreach($this->req['id_faktur_pasien_item'] as $i => $id_faktur_pasien_item):
                    $item = data_faktur_pasien_item::where('id_faktur_pasien_item', $id_faktur_pasien_item);
                    $item->update([
                        'qty' => $this->req['qty_faktur_pasien_campur'][$i],
                        'harga' => $this->req['biaya_faktur_pasien_campur'][$i],
                        'diskon' => $this->req['diskon_faktur_pasien_campur'][$i],
                        'subtotal' => $this->req['total_faktur_pasien_campur'][$i],
                    ]);

                    // Pendapatan Resep
                    data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_pendapatan'][$i])
                        ->where('id_option', $id_faktur_pasien_item)
                        ->update([
                            'tanggal' => date('Y-m-d'),
                            'debit' => 0,
                            'kredit' => $this->req['total_faktur_pasien_campur'][$i]
                        ]);


                    $harga_beli_resep_item = $this->req['harga_beli_item'][$i] * $this->req['qty_faktur_pasien_campur'][$i];
                    // =========================== voucer_persediaan ========================
                    // Persediaan Baarang
                    data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_persediaan'][$i])
                        ->where('id_option', $id_faktur_pasien_item)
                        ->update([
                            'tanggal' => date('Y-m-d'),
                            'debit' => 0,
                            'kredit' => $harga_beli_resep_item,
                        ]);


                    // Biaya HPP
                    data_jurnal::where('tipe_jurnal', $this->req['tipe_jurnal_hpp'][$i])
                        ->where('id_option', $id_faktur_pasien_item)
                        ->update([
                            'tanggal' => date('Y-m-d'),
                            'debit' => $harga_beli_resep_item,
                            'kredit' => 0,
                        ]);


                endforeach;
            endif;

            // Perubahan status biling
            if($this->req['grandtotal'] > $amount_due)
                $status = 1;
            else if($this->req['grandtotal'] <= $amount_due)
                $status = 2;

           
            if($amount_due > $this->req['grandtotal'])
                $amount_due = $this->req['grandtotal'];

            $faktur->update([
                'id_payment_terms' => $this->req['terms'],
                'prefix' => $this->req['prefix'],
                'duodate' => date('Y-m-d', strtotime($this->req['duodate'])),
                'adjustment' => $this->req['adjustment'],
                'subtotal' => $this->req['subtotal'],
                'amount_due' => $amount_due,
                'total' => $this->req['grandtotal'],
                'status' => $status
            ]);

            //dd([$this->req['grandtotal'], $faktur->total]);

            $sisa = $this->req['grandtotal'] - $amount_due;
            $piutang = data_piutang_pasien::where('id_faktur', $faktur->id_faktur)->where('id_pasien', $faktur->id_pasien)->first();
            if($piutang != null){
                $piutang->update([
                    'total' => $sisa,
                    'status' => empty($sisa) ? 2 : 1
                ]);
            }

            \DB::commit();

            return [
                'label' => 'success',
                'err' => 'Berhasil dipebaharui'
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
