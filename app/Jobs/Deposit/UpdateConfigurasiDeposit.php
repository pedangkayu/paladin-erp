<?php

namespace App\Jobs\Deposit;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\models\data_config_coa_deposit;

class UpdateConfigurasiDeposit extends Job implements SelfHandling
{
   public $req;

    public function __construct(array $req) {
        $this->req = $req;
    }

    public function handle() {
        
        try {
            \DB::begintransaction();


            data_config_coa_deposit::where('aktif', 1)->update([
                'aktif' => 0
            ]);
                data_config_coa_deposit::create([
                'coa_deposit'         => $this->req['coa_deposit'],
                'coa_pembayaran_cash' => $this->req['coa_pembayaran_cash'],
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
