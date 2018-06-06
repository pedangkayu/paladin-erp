<?php

namespace App\Jobs\Pinjaman;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_config_coa_loan;

class UpdateconfigCoaLoanJob extends Job implements SelfHandling
{
    public $req;

     public function __construct(array $req) {
         $this->req = $req;
     }

     public function handle() {

         try {
             \DB::begintransaction();

             data_config_coa_loan::where('aktif', 1)->update([
                 'aktif' => 0
             ]);
                 data_config_coa_loan::create([
                 'coa_loan'         => $this->req['coa_loan'],
                 'coa_pembayaran_cash' => $this->req['coa_pembayaran_cash_loan'],
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
