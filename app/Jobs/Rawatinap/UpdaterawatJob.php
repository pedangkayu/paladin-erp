<?php

namespace App\Jobs\RawatInap;

use App\Models\data_rawat_inap_pakai;
use App\Models\data_rawat_inap;
use App\Models\data_log_pasien;
use App\Models\data_pasien;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdaterawatJob extends Job implements SelfHandling
{  public $req;
 
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
        {
        //dd($this->req);
        try{

            \DB::begintransaction();

        $data = data_rawat_inap::firstOrCreate([
            'id_antrian'                 => $this->data->ID_ANTRIAN_RWT_INAP,//
            'daftar_rinap'               => $this->data->TGL_PENDAFTARAN_RWT_INAP,//
            'id_karyawan'                => $this->data->ID_PGW,
            'id_pasien'                  => $this->data->ID_PASIEN,
            'id_layanan_rs'              => $this->data->ID_LAYANAN_RS,
            'rencana_mrs'                => $this->data->RENCANA_MRS,
            'status_masuk_rinap'         => $this->data->STATUS_MASUK_RWT_INAP,
            ]);
        data_rawat_inap::find($data->id_rinap)->update([
            'id_rs'                      => $this->data->ID_RS,
            'id_status_jenis_kedatangan' => $this->data->ID_STATUS_JENIS_KEDATANGAN,
            'rencana_krs'                => $this->data->RENCANA_KRS,
            'keterangan'                 => $this->data->KETERANGAN_RWT_INAP, 
            'status_keluar_rinap'        => $this->data->STATUS_KELUAR_RWT_INAP,
            'ket_mrs'                    => $this->data->Ket_MRS,
              ]);
         //--------------------------------------------------//
         $pakai=data_rawat_inap_pakai::firstOrCreate([
             'id_rinap' => $data->id_rinap,
           'id_antrian'     => $this->data->ID_ANTRIAN_RWT_INAP,
                        ]);
          data_rawat_inap_pakai::find($pakai->id_rinap_pakai)->update([
              'id_rinap_pakai_hc' => $this->data->ID_PAKAI_KAMAR,
             'tgl_pakai'         => $this->data->TGL_MULAI_PAKAI_KAMAR,
             'tgl_mulai_tagihan' => $this->data->TGL_MULAI_TAGIHAN,
             'id_kamar'          => $this->data->ID_KAMAR_RWT_INAP,
            'no_tagihan'        => $this->data->NO_TAGIHAN,
            'selesai_rinap'     => $this->data->TGL_SELESAI_PAKAI_KAMAR,
            'No_trans'          => 1,
            'daftar_rinap'      => $this->data->TGL_PENDAFTARAN_RWT_INAP,

            ]);
         //---------------------------------------------------//
        $pasien = data_pasien::firstOrCreate([
        'id_pasien_hc'                  => $this->data->ID_PASIEN,
        ]);
        data_pasien::find($pasien->id_pasien)->update([
                'id_perpenjamin'                => $this->data->ID_PERPENJAMIN,
                'id_layanan_rs'                 => $this->data->ID_LAYANAN_RS,
                'id_pgw'                        => $this->data->ID_PGW,
                'nama_pasien'                   => $this->data->NAMA_PASIEN,
                'noktp_pasien'                  => $this->data->NOKTP_PASIEN,
                'noasuransi_pasien'             => $this->data->NOASURANSI_PASIEN,
                'jk_pasien'                     => $this->data->JK_PASIEN,
                'status_nikah_pasien'           => $this->data->STATUS_NIKAH_PASIEN,
                'tgllahir_pasien'               => $this->data->TGLLAHIR_PASIEN,
                'tempatlahir_pasien'            => $this->data->TEMPATLAHIR_PASIEN,
                'agama_pasien'                  => $this->data->AGAMA_PASIEN,
                'warga_negara_pasien'           => $this->data->WARGA_NEGARA_PASIEN,
                'pendidikan_pasien'             => $this->data->PENDIDIKAN_PASIEN,
                'alamat_pasien'                 => $this->data->ALAMAT_PASIEN,
                'kodepos_pasien'                => $this->data->KODEPOS_PASIEN,
                'kecamatan_pasien'              => $this->data->KECAMATAN_PASIEN,
                'kelurahan_pasien'              => $this->data->kelurahan_pasien,
                'kota_pasien'                   => $this->data->KOTA_PASIEN,
                'telp_asal'                     => $this->data->TELP_ASAL,
                'alamat_disurabaya_pasien'      => $this->data->ALAMAT_DISURABAYA_PASIEN,
                'kodepos_surabaya'              => $this->data->KODEPOS_SURABAYA,
                'telp_pasien'                   => $this->data->TELP_PASIEN,
                'hp_pasien'                     => $this->data->HP_PASIEN,
                'e_mail_pasien'                 => $this->data->E_MAIL_PASIEN,
                'instansi_pasien'               => $this->data->INSTANSI_PASIEN,
                'alamat_kantor_pasien'          => $this->data->ALAMAT_KANTOR_PASIEN,
                'kode_pos_kantor_pasien'        => $this->data->KODE_POS_KANTOR_PASIEN,
                'telp_kantor_pasien'            => $this->data->TELP_KANTOR_PASIEN,
                'nama_suami_pasien'             => $this->data->NAMA_SUAMI_PASIEN,
                'pekerjaan_suami_pasien'        => $this->data->PEKERJAAN_SUAMI_PASIEN,
                'nama_ayah_kandung_pasien'      => $this->data->NAMA_AYAH_KANDUNG_PASIEN,
                'pekerjaan_ayah_kandung_pasien' => $this->data->PEKERJAAN_AYAH_KANDUNG_PASIEN,
                'Jenis_pembayaran'              => $this->data->Jenis_pembayaran,
                'penanggung_biaya_pasien'       => $this->data->PENANGGUNG_BIAYA_PASIEN,
                'nama_ang_kel_pasien'           => $this->data->NAMA_ANG_KEL_PASIEN,
                'alamat_ang_kel_pasien'         => $this->data->ALAMAT_ANG_KEL_PASIEN,
                'kode_pos_ang_kel_pasien'       => $this->data->KODE_POS_ANG_KEL_PASIEN,
                'telp_ang_kel_pasien'           => $this->data->TELP_ANG_KEL_PASIEN,
                'hp_ang_kel_pasien'             => $this->data->HP_ANG_KEL_PASIEN,
                'e_mail_ang_kel_pasien'         => $this->data->E_MAIL_ANG_KEL_PASIEN,
                'hubungan_ang_kel_pasien'       => $this->data->HUBUNGAN_ANG_KEL_PASIEN,
                'kodekota_pasien'               => $this->data->KODEKOTA_PASIEN,
                'provinsi_pasien'               => $this->data->PROVINSI_PASIEN,
                'pekerjaan_pasien'              => $this->data->PEKERJAAN_PASIEN,
                'foto_pasien'                   => $this->data->FOTO_PASIEN,
                'logtime_pegawai_entry_mr4'     => $this->data->LOGTIME_PEGAWAI_ENTRY_MR4,
                'tanggal_daftar'                => $this->data->TANGGAL_DAFTAR,
                'suku'                          => $this->data->SUKU,
                'negara_pasien'                 => $this->data->NEGARA_PASIEN,
                'rsos_brosur'                   => $this->data->rsos_brosur,
                'rsos_news'                     => $this->data->rsos_news,
                'rsos_health'                   => $this->data->rsos_health,
                'company'                       => $this->data->company,
                'rujukan'                       => $this->data->rujukan,
                'internet'                      => $this->data->internet,
                'keluarga'                      => $this->data->keluarga,
                'rujukan_ket'                   => $this->data->rujukan_ket,
                'internet_ket'                  => $this->data->internet_ket,
                'keluarga_ket'                  => $this->data->keluarga_ket,
                'others'                        => $this->data->others,
                'rsos_health_ket'               => $this->data->rsos_health_ket,
                'company_ket'                   => $this->data->company_ket,
                'others_ket'                    => $this->data->others_ket,
                'Status_BC'                     => $this->data->Status_BC,
                'tgl_bc'                        => $this->data->tgl_bc,
                'status_dead'                   => $this->data->status_dead,
                'tgl_dead'                      => $this->data->tgl_dead,
                'status_drm_keluar'             => $this->data->status_drm_keluar,
                'time_add'                      => $this->data->time_add,
                'time_fin'                      => $this->data->time_fin,
                'nama_ibu'                      => $this->data->NAMA_IBU,
                'flag_daftar'                   => $this->data->flag_daftar,
                'log_start'                     => $this->data->log_start,
                'log_stop'                      => $this->data->log_stop,
                'bahasa_pasien'                 => $this->data->bahasa_pasien,
                'penerjemah'                    => $this->data->penerjemah,
                'retensi'                       => $this->data->retensi,
                'status_hsl'                    => $this->data->status_hsl,
                'comment'                       => $this->data->comment,
                'instansi_suami'                => $this->data->instansi_suami,
                'instansi_ayah'                 => $this->data->instansi_ayah,
                'meninggal_ket'                 => $this->data->meninggal_ket,
            ]);

        ///---------------------------------------------------///
         // $log =data_log_pasien::create([
         //    'id_pasien'     => $this->data->ID_PASIEN,
         //    'id_layanan'    =>$data->id_rinap,
         //    'tipe'          =>3,
         //    'no_antrian_hc' =>$data->id_antrian,
         //    'nama_pasien'   =>$this->data->NAMA_PASIEN,
         //    'status'        =>1,
         //     ]);



        \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Check Out Pasien Berhasil. '
            ];

        }catch(\Exception $e){
            \DB::rollback();

            return [
                'error'  => $e->getMessage()
            ];
        }

        
    }

}