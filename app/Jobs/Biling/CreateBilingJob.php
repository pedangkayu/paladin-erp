<?php

namespace App\Jobs\Biling;

use App\Models\data_resep;
use App\Models\data_faktur;
use App\Models\data_treatment;
use App\Models\data_log_pasien;
use App\Models\data_faktur_item;
use App\Models\data_faktur_pasien;
use App\Models\data_faktur_pasien_item;

use App\Models\ref_coa;
use App\Models\ref_satuan;
use App\Models\data_jurnal;
use App\Models\data_voucer_jurnal;
use App\Models\data_config_coa_biling as Config;

use App\Models\data_piutang_pasien;

use App\Models\Views\view_biling;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateBilingJob extends Job implements SelfHandling {

    public $req;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $req) {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        
        $me = \Me::data()->id_karyawan;
        $config =  Config::active()->first();
        $status = 0;
        //dd($this->req);
        try{

            \DB::begintransaction();

            if(empty($this->req['grandtotal']))
                throw new \Exception("Tidak ditemukan total harga", 1);
                

            $seri = view_biling::whereTahun(date('Y'))->first();
            $seri = empty($seri->jumlah) ? 1 : ($seri->jumlah + 1);
            $no_faktur = 'INV/' . date('dmY') . '/RSOS/' . sprintf("%07d", $seri);

            $faktur = data_faktur::create([
                'nomor_faktur' => $no_faktur,
                'type' => 2,
                'prefix' => $this->req['prefix'],
                'id_pasien' => $this->req['id_pasien'],
                'tgl_faktur' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                'duodate' => date('Y-m-d', strtotime($this->req['duodate'])),
                'id_payment_terms' => $this->req['terms'],
                'diskon' => $this->req['diskon_all'],
                'adjustment' => $this->req['adjustment'],
                'subtotal' => $this->req['subtotal'],
                'total' => $this->req['grandtotal'],
                'keterangan' => $this->req['keterangan'],
                'status' => 0, // Belum lunas
                'id_karyawan' => $me
            ]);



            // Pembuatan Voucer
            $voucer_pendapatan = data_voucer_jurnal::create([
                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                'keterangan' => 'Pembuatan faktur #' . $no_faktur
            ]);

            if(!empty($this->req['id_item_obat_resep']) || !empty($this->req['id_bhp']))
                $voucer_persediaan = data_voucer_jurnal::create([
                    'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                    'keterangan' => 'Persediaan dari transaksi faktur #' . $no_faktur
                ]);

            // adjustment
            if($this->req['adjustment'] > 0)
                data_jurnal::create([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_adjustment,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                    'deskripsi' => 'Adjustment faktur #' . $no_faktur,
                    'id_payment_methode' => 0,
                    'debit' => $this->req['adjustment'],
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 3,
                    'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                    'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                ]);

            // Piutang Usaha
            $coa = ref_coa::find($config->coa_sebelum_dibayar);
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $config->coa_sebelum_dibayar,
                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                'deskripsi' => $coa->nm_coa . ' faktur #' . $no_faktur,
                'id_payment_methode' => 0,
                'debit' => $this->req['grandtotal'],
                'kredit' => 0,
                'id_karyawan' => $me,
                'tipe_jurnal' => 7,
                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
            ]);

            data_piutang_pasien::create([
                'id_faktur' => $faktur->id_faktur,
                'id_pasien' => $this->req['id_pasien'],
                'status' => 1,
                'total' => $this->req['grandtotal'],
                'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
            ]);

            // Menyimpan item tambahan
            if(!empty($this->req['add_tindakan_uraian'])){

                foreach($this->req['add_tindakan_uraian'] as $a => $uraian){
                    $mi = data_faktur_item::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_item' => 0,
                        'deskripsi' => $uraian,
                        'qty' => $this->req['add_tindakan_qty'][$a],
                        'id_satuan' => 0,
                        'harga' => $this->req['add_tindakan_biaya'][$a],
                        'diskon' => $this->req['add_tindakan_diskon'][$a],
                        'total' => $this->req['add_tindakan_jumlah'][$a]
                    ]);

                    // Item tambahan Biling
                    data_jurnal::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_coa' =>  $config->coa_item_tambahan,
                        'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                        'deskripsi' => $uraian . ' jumlah ' . $this->req['add_tindakan_qty'][$a],
                        'id_payment_methode' => 0,
                        'debit' => 0,
                        'kredit' => $this->req['add_tindakan_jumlah'][$a],
                        'id_karyawan' => $me,
                        'tipe_jurnal' => 8,
                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                        'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                        'id_option' => $mi->id_faktur_item
                    ]);

                }

            }

            // PENYIMPANAN DATA RESEP
            if(!empty($this->req['id_resep'])): /* Kondisi resep */

                foreach($this->req['id_resep'] as $i => $id_resep):

                    
                        foreach($this->req['id_resep_item'][$id_resep] as $ii => $id_resep_item):
                        
                            $item_resep = data_faktur_pasien::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_resep' => $id_resep,
                                'id_resep_item' => $id_resep_item,
                                'qty' => $this->req['qty_resep_item'][$id_resep][$ii],
                                'id_satuan' => $this->req['id_satuan_resep_item'][$id_resep][$ii],
                                'id_item' => $this->req['id_item_obat_resep'][$id_resep][$ii],
                                'diskon' => $this->req['diskon_resep_item'][$id_resep][$ii],
                                'harga_jual' => $this->req['harga_jual_resep_item'][$id_resep][$ii],
                                'subtotal' => $this->req['subtotal_resep_item'][$id_resep][$ii],
                            ]);


                            $satuan = ref_satuan::find($this->req['id_satuan_resep_item'][$id_resep][$ii]);
                            $sat = $satuan != null ? $satuan->nm_satuan : '';
                            $desc = $this->req['nama_barang'][$id_resep][$ii] . ' ' . $this->req['qty_resep_item'][$id_resep][$ii] . ' ' . $sat;

                            // COA RESEP
                            $harga_beli_resep_item = $this->req['harga_beli_resep_item'][$id_resep][$ii] * $this->req['qty_resep_item'][$id_resep][$ii];
                            // Pendapatan Resep
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $config->coa_pendapatan_resep,
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $desc,
                                'id_payment_methode' => 0,
                                'debit' => 0,
                                'kredit' => $this->req['subtotal_resep_item'][$id_resep][$ii],
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 9,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                'id_option' => $item_resep->id_faktur_pasien
                            ]);

                            // =========================== voucer_persediaan ========================
                            // Persediaan Baarang
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $this->req['resep_coa_persediaan'][$id_resep][$ii],
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $desc,
                                'id_payment_methode' => 0,
                                'debit' => 0,
                                'kredit' => $harga_beli_resep_item,
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 10,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                'id_option' => $item_resep->id_faktur_pasien
                            ]);


                            // Biaya HPP
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $this->req['resep_coa_biaya'][$id_resep][$ii],
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $desc,
                                'id_payment_methode' => 0,
                                'debit' => $harga_beli_resep_item,
                                'kredit' => 0,
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 11,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                'id_option' => $item_resep->id_faktur_pasien
                            ]);


                            /* Resep Campur */
                            if($this->req['id_item_obat_resep'][$id_resep][$ii] == 0){

                                foreach($this->req['id_resep_campur'][$id_resep][$id_resep_item] as $cm => $id_resep_campur){
                                    $campur = data_faktur_pasien_item::create([
                                        'id_faktur_pasien' => $item_resep->id_faktur_pasien,
                                        'id_resep_campur' => $id_resep_campur,
                                        'id_barang' => $this->req['id_barang_item_campur'][$id_resep][$id_resep_item][$cm],
                                        'qty' => $this->req['qty_item_campur'][$id_resep][$id_resep_item][$cm],
                                        'id_satuan' => $this->req['id_satuan_item_campur'][$id_resep][$id_resep_item][$cm],
                                        'harga' => $this->req['harga_item_campur'][$id_resep][$id_resep_item][$cm],
                                        'diskon' => $this->req['diskon_campur_item'][$id_resep][$id_resep_item][$cm],
                                        'subtotal' => $this->req['subtotal_item_campur'][$id_resep][$id_resep_item][$cm],
                                    ]);

                                    $satuan = ref_satuan::find($this->req['id_satuan_item_campur'][$id_resep][$id_resep_item][$cm]);
                                    $sat = $satuan != null ? $satuan->nm_satuan : '';
                                    $desc = $this->req['nm_barang'][$id_resep][$id_resep_item][$cm] . ' ' . $this->req['qty_item_campur'][$id_resep][$id_resep_item][$cm] . ' ' . $sat;

                                    $total_h_beli = $this->req['harga_beli_item_campur'][$id_resep][$id_resep_item][$cm] * $this->req['qty_item_campur'][$id_resep][$id_resep_item][$cm];
                                    
                                    // Pendapatan Resep <======================================= (harga jual - harga beli)
                                    data_jurnal::create([
                                        'id_faktur' => $faktur->id_faktur,
                                        'id_coa' =>  $config->coa_pendapatan_resep,
                                        'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                        'deskripsi' => $desc,
                                        'id_payment_methode' => 0,
                                        'debit' => 0,
                                        'kredit' => $this->req['subtotal_item_campur'][$id_resep][$id_resep_item][$cm],
                                        'id_karyawan' => $me,
                                        'tipe_jurnal' => 25,
                                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                        'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                        'id_option' => $campur->id_faktur_pasien_item
                                    ]);

                                    // ===================================== voucer_persediaan ============================

                                    // Persediaan Baarang
                                    data_jurnal::create([
                                        'id_faktur' => $faktur->id_faktur,
                                        'id_coa' =>  $this->req['resep_campur_coa_persediaan'][$id_resep][$id_resep_item][$cm],
                                        'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                        'deskripsi' => $desc,
                                        'id_payment_methode' => 0,
                                        'debit' => 0,
                                        'kredit' => $total_h_beli,
                                        'id_karyawan' => $me,
                                        'tipe_jurnal' => 26,
                                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                        'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                        'id_option' => $campur->id_faktur_pasien_item
                                    ]);


                                    // Biaya HPP
                                    data_jurnal::create([
                                        'id_faktur' => $faktur->id_faktur,
                                        'id_coa' =>  $this->req['resep_campur_coa_biaya'][$id_resep][$id_resep_item][$cm],
                                        'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                        'deskripsi' => $desc,
                                        'id_payment_methode' => 0,
                                        'debit' => $total_h_beli,
                                        'kredit' => 0,
                                        'id_karyawan' => $me,
                                        'tipe_jurnal' => 27,
                                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                        'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                        'id_option' => $campur->id_faktur_pasien_item
                                    ]);

                                    

                                }


                            }
                            /* End Resep Campur */

                        endforeach;
                   
                    /* Update status Resep menjadi 2 */
                    data_resep::find($id_resep)->update([
                        'status' => 2
                    ]);
                    data_log_pasien::where('id_layanan', $id_resep)->where('tipe', 1)->update([
                        'status' => 2
                    ]);

                    /* INSERT JURNAL RESEP */
                    

                endforeach;

            endif; /* End Kondisi resep */
            // End PENYIMPANAN DATA RESEP


            // Penimpanan data Treatment

            if(!empty($this->req['id_treatment'])):
                foreach($this->req['id_treatment'] as $i => $id_treatment):

                    foreach($this->req['id_treatment_item'][$id_treatment] as $tt => $id_treatment_item):
                        $dikon_dr = ($this->req['tarif_dr_treatment'][$id_treatment][$tt] * $this->req['diskon_treatment_item'][$id_treatment][$tt]) / 100;
                        $tarif_dr = $this->req['tarif_dr_treatment'][$id_treatment][$tt] - $dikon_dr;

                        $item_treatment = data_faktur_pasien::create([
                            'id_faktur' => $faktur->id_faktur,
                            'id_treatment' => $id_treatment,
                            'id_treatment_item' => $id_treatment_item,
                            'tarif_dasar' => $this->req['tarif_dasar_treatment'][$id_treatment][$tt],
                            'tarif_dr' => $tarif_dr,
                            'tarif_dr_real' => $this->req['tarif_dr_treatment'][$id_treatment][$tt],
                            'persen_dr' => $this->req['persen_dr_treatment'][$id_treatment][$tt],
                            'tarif_rs' => $this->req['tarif_rs_treatment'][$id_treatment][$tt],
                            'persen_rs' => $this->req['persen_rs_treatment'][$id_treatment][$tt],
                            'persen_dr_real' => $this->req['persen_dr_real'][$id_treatment][$tt],
                            'diskon' => $this->req['diskon_treatment_item'][$id_treatment][$tt],
                            'subtotal' => $this->req['subtotal_treatment'][$id_treatment][$tt],
                        ]);
                        
                        if($this->req['tipe_treatment'][$id_treatment][$tt] == 2):

                            // Grand total Pendapatan RS
                            /*
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $this->req['coa_treatment'][$id_treatment][$tt],
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $this->req['nama_treatment_item'][$id_treatment][$tt],
                                'id_payment_methode' => 0,
                                'debit' => 0,
                                'kredit' => $this->req['subtotal_treatment'][$id_treatment][$tt],
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 14,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                'id_option' => $item_treatment->id_faktur_pasien
                            ]);
                            */

                            // Biaya Dokter 80% - Diskon
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $this->req['coa_dr_treatment'][$id_treatment][$tt],
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $this->req['nama_treatment_item'][$id_treatment][$tt],
                                'id_payment_methode' => 0,
                                'debit' => 0,
                                'kredit' => $tarif_dr,
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 12,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                'id_option' => $item_treatment->id_faktur_pasien
                            ]);


                            // Biaya 20% Rumasakit
                            data_jurnal::create([
                                'id_faktur' => $faktur->id_faktur,
                                'id_coa' =>  $this->req['coa_rs_treatment'][$id_treatment][$tt],
                                'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                'deskripsi' => $this->req['nama_treatment_item'][$id_treatment][$tt],
                                'id_payment_methode' => 0,
                                'debit' => 0,
                                'kredit' => $this->req['tarif_rs_treatment'][$id_treatment][$tt],
                                'id_karyawan' => $me,
                                'tipe_jurnal' => 13,
                                'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                'id_option' => $item_treatment->id_faktur_pasien
                            ]);

                        endif;

                        /* INSERT BHP */

                        if(!empty($this->req['id_bhp'][$id_treatment][$id_treatment_item])){
                            foreach($this->req['id_bhp'][$id_treatment][$id_treatment_item] as $b => $id_bhp){
                                $bhp = data_faktur_pasien_item::create([
                                    'id_faktur_pasien' => $item_treatment->id_faktur_pasien,
                                    'id_bhp' => $id_bhp,
                                    'id_barang' => $this->req['id_barang'][$id_treatment][$id_treatment_item][$b],
                                    'qty' => $this->req['qty_bhp'][$id_treatment][$id_treatment_item][$b],
                                    'id_satuan' => $this->req['id_satuan_bhp'][$id_treatment][$id_treatment_item][$b],
                                    'harga' => $this->req['harga_bhp'][$id_treatment][$id_treatment_item][$b],
                                    'diskon' => $this->req['diskon_bhp'][$id_treatment][$id_treatment_item][$b],
                                    'subtotal' => $this->req['subtotal_bhp'][$id_treatment][$id_treatment_item][$b],
                                ]);


                                $satuan = ref_satuan::find($this->req['id_satuan_bhp'][$id_treatment][$id_treatment_item][$b]);
                                $sat = $satuan != null ? $satuan->nm_satuan : '';
                                $desc = $this->req['treatment_item_nm_barang'][$id_treatment][$id_treatment_item][$b] . ' ' . $this->req['qty_bhp'][$id_treatment][$id_treatment_item][$b] . ' ' . $sat;
                                
                                // Jurnal BHP
                                $total_h_beli = $this->req['harga_beli_bhp'][$id_treatment][$id_treatment_item][$b] * $this->req['qty_bhp'][$id_treatment][$id_treatment_item][$b];
                                
                                // Pendapatan BHP <======================================= (harga jual - harga beli)
                                data_jurnal::create([
                                    'id_faktur' => $faktur->id_faktur,
                                    'id_coa' =>  $this->req['treatment_item_coa_pendapatan'][$id_treatment][$id_treatment_item][$b],
                                    'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                    'deskripsi' => $desc,
                                    'id_payment_methode' => 0,
                                    'debit' => 0,
                                    'kredit' => $this->req['subtotal_bhp'][$id_treatment][$id_treatment_item][$b],
                                    'id_karyawan' => $me,
                                    'tipe_jurnal' => 17,
                                    'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                    'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                                    'id_option' => $bhp->id_faktur_pasien_item
                                ]);

                                // ===================================== voucer_persediaan ==================================

                                // Persediaan Baarang
                                data_jurnal::create([
                                    'id_faktur' => $faktur->id_faktur,
                                    'id_coa' =>  $this->req['treatment_item_coa_persediaan'][$id_treatment][$id_treatment_item][$b],
                                    'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                    'deskripsi' => $desc,
                                    'id_payment_methode' => 0,
                                    'debit' => 0,
                                    'kredit' => $total_h_beli,
                                    'id_karyawan' => $me,
                                    'tipe_jurnal' => 15,
                                    'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                    'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                    'id_option' => $bhp->id_faktur_pasien_item
                                ]);


                                // Biaya HPP
                                data_jurnal::create([
                                    'id_faktur' => $faktur->id_faktur,
                                    'id_coa' =>  $this->req['treatment_item_coa_biaya'][$id_treatment][$id_treatment_item][$b],
                                    'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                                    'deskripsi' => $desc,
                                    'id_payment_methode' => 0,
                                    'debit' => $total_h_beli,
                                    'kredit' => 0,
                                    'id_karyawan' => $me,
                                    'tipe_jurnal' => 16,
                                    'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                                    'id_voucer_jurnal' => $voucer_persediaan->id_voucer_jurnal,
                                    'id_option' => $bhp->id_faktur_pasien_item
                                ]);


                            }
                        }

                        /* END INSERT BHP */

                    endforeach;


                    /* Update status Treatment menjadi 2 */
                    data_treatment::find($id_treatment)->update([
                        'status' => 2
                    ]);
                    data_log_pasien::where('id_layanan', $id_treatment)->where('tipe', 2)->update([
                        'status' => 2
                    ]);

                endforeach;
            endif;

            // End Penimpanan data Treatment            


            // Penyimpanan data Rinap
            if(!empty($this->req['id_rinap'])):
                foreach($this->req['id_rinap'] as $i => $id_rinap):
                    $rinap = data_faktur_pasien::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_rinap' => $id_rinap,
                        'id_kamar' => $this->req['id_kamar'][$i],
                        'tarif_kamar' => $this->req['tarif_kamar'][$i],
                        'tarif_dasar_rinap' => $this->req['tarif_dasar_rinap'][$i],
                        'check_in' => date('Y-m-d H:i:s', strtotime($this->req['check_in'][$i])),
                        'check_out' => date('Y-m-d H:i:s', strtotime($this->req['check_out'][$i])),
                        'total_sewa' => $this->req['total_sewa'][$i],
                        'diskon_rinap' => $this->req['diskon_rinap'][$i],
                    ]);


                    // Pendapatan Rinap
                    data_jurnal::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_coa' =>  $config->coa_rawat_inap,
                        'tanggal' => date('Y-m-d', strtotime($this->req['tgl_faktur'])),
                        'deskripsi' => $this->req['nm_kamar'][$i],
                        'id_payment_methode' => 0,
                        'debit' => 0,
                        'kredit' => $this->req['tarif_kamar'][$i],
                        'id_karyawan' => $me,
                        'tipe_jurnal' => 28,
                        'link_slug' => '/biling/edit/' . $faktur->id_faktur,
                        'id_voucer_jurnal' => $voucer_pendapatan->id_voucer_jurnal,
                        'id_option' => $rinap->id_faktur_pasien
                    ]);


                    data_log_pasien::where('id_layanan', $id_rinap)->where('tipe', 3)->update([
                        'status' => 2
                    ]);

                endforeach;

            endif;
            // End Penyimpanan data treatment

            \DB::commit();

            return [
                'result' => true,
                'id_faktur' => $faktur->id_faktur,
                'label' => 'success',
                'err' => 'Faktur berhasil di buat dengan No. #' . $no_faktur
            ];

        }catch(\Exception $e){

            \DB::rollback();
            return [
                'result' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];

        }

    }
}