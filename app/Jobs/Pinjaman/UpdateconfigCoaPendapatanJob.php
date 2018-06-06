<?php

namespace App\Jobs\Pinjaman;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_config_coa_pendapatan;

class UpdateconfigCoaPendapatanJob extends Job implements SelfHandling
{
    public $req;

     public function __construct(array $req) {
         $this->req = $req;
     }

     public function handle() {

         try {
             \DB::begintransaction();

             data_config_coa_pendapatan::where('aktif', 1)->update([
                 'aktif' => 0
             ]);
                 data_config_coa_pendapatan::create([
                    'coa_pendapatan_lainnya' => $this->req['coa_pendapatan_lainnya'],
             		'coa_adjustment'              => $this->req['coa_adjustment_pendapatan'],
             		'coa_piutang'                 => $this->req['coa_piutang'],
             		'coa_ppn'                     => $this->req['coa_ppn_pendapatan'],
             		'coa_pembayaran_cash'         => $this->req['coa_pembayaran_cash_pendapatan'],
                 ]);

             \DB::commit();

             return [
                 'label' => 'success',
                 'err' => 'Akun pendapatan berhasil diperbaharui'
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
