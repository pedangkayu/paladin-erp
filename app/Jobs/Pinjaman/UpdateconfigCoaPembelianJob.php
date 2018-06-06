<?php

namespace App\Jobs\Pinjaman;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_config_coa_pembelian;

class UpdateconfigCoaPembelianJob extends Job implements SelfHandling
{
    public $req;

     public function __construct(array $req) {
         $this->req = $req;
     }

     public function handle() {

         try {
             \DB::begintransaction();

             data_config_coa_pembelian::where('aktif', 1)->update([
                 'aktif' => 0
             ]);
                 data_config_coa_pembelian::create([
                    'coa_ppn'               => $this->req['coa_ppn_pembelian'],
             		'coa_adjustment'             => $this->req['coa_adjustment_pembelian'],
             		'coa_jumlah_sebelum_dibayar' => $this->req['coa_jumlah_sebelum_dibayar_pembelian'],
             		'coa_penambahan_item'        => $this->req['coa_penambahan_item_pembelian'],
             		'coa_pembayaran_cash'        => $this->req['coa_pembayaran_cash_pembelian'],
                 ]);

             \DB::commit();

             return [
                 'label' => 'success',
                 'err' => 'Akun berhasil diperbaharui'
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
