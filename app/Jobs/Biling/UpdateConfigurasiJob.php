<?php

namespace App\Jobs\Biling;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_config_coa_biling;

class UpdateConfigurasiJob extends Job implements SelfHandling {
    
    public $req;

    public function __construct(array $req) {
        $this->req = $req;
    }

    public function handle() {
        
        try {
            \DB::begintransaction();


            data_config_coa_biling::where('aktif', 1)->update([
                'aktif' => 0
            ]);
            data_config_coa_biling::create([
                'coa_sebelum_dibayar' => $this->req['coa_sebelum_dibayar'],
                'coa_adjustment' => $this->req['coa_adjustment'],
                'coa_item_tambahan' => $this->req['coa_item_tambahan'],
                'coa_rawat_inap' => $this->req['coa_rawat_inap'],
                'coa_saldo' => $this->req['coa_saldo'],
                'coa_pendapatan_resep' => $this->req['coa_pendapatan_resep']
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
