<?php

namespace App\Jobs\Treatment;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_pasien;
use App\Models\data_rawat_inap;

class InsertRawatInapJob extends Job implements SelfHandling
{
    public $req;

    public function __construct($pa)
    {
        $this->pa=$pa;
    }

    public function handle()
    {
        // dd($this->pa);
        try{
            \DB::begintransaction();
              $pa = data_rawat_inap::firstOrCreate([
                    'id_antrian'                 => $this->pa->ID_ANTRIAN_RWT_INAP,
                    'daftar_rinap'               => $this->pa->TGL_PENDAFTARAN_RWT_INAP,
                    'id_rs'                      => $this->pa->ID_RS,
                    'id_karyawan'                => $this->pa->ID_PGW,
                    'id_status_jenis_kedatangan' => $this->pa->ID_STATUS_JENIS_KEDATANGAN,
                    'id_pasien'                  => $this->pa->ID_PASIEN,
                    'id_layanan_rs'              => $this->pa->ID_LAYANAN_RS,
                    'rencana_mrs'                => $this->pa->RENCANA_MRS,
                    'rencana_krs'                => $this->pa->RENCANA_KRS,
                    'keterangan'                 => $this->pa->KETERANGAN_RWT_INAP,
                    'status_masuk_rinap'         => $this->pa->STATUS_MASUK_RWT_INAP,
                    'status_keluar_rinap'        => $this->pa->STATUS_KELUAR_RWT_INAP,
                    'ket_mrs'                    =>$this->pa->Ket_MRS,
                ]);

              $pasien = data_pasien::firstOrCreate([
                'id_pasien_hc'                  => $this->pa->ID_PASIEN,
                ]);
        data_pasien::find($pasien->id_pasien)->update([
                'id_perpenjamin'                => $this->pa->ID_PERPENJAMIN,
                'id_layanan_rs'                 => $this->pa->ID_LAYANAN_RS,
                'id_pgw'                        => $this->pa->ID_PGW,
                'nama_pasien'                   => $this->pa->NAMA_PASIEN,
                'noktp_pasien'                  => $this->pa->NOKTP_PASIEN,
                'noasuransi_pasien'             => $this->pa->NOASURANSI_PASIEN,
                'jk_pasien'                     => $this->pa->JK_PASIEN,
                'status_nikah_pasien'           => $this->pa->STATUS_NIKAH_PASIEN,
                'tgllahir_pasien'               => $this->pa->TGLLAHIR_PASIEN,
                'tipe'                          =>1,
                'tempatlahir_pasien'            => $this->pa->TEMPATLAHIR_PASIEN,
                'agama_pasien'                  => $this->pa->AGAMA_PASIEN,
                'warga_negara_pasien'           => $this->pa->WARGA_NEGARA_PASIEN,
                'pendidikan_pasien'             => $this->pa->PENDIDIKAN_PASIEN,
                'alamat_pasien'                 => $this->pa->ALAMAT_PASIEN,
                'kodepos_pasien'                => $this->pa->KODEPOS_PASIEN,
                'kecamatan_pasien'              => $this->pa->KECAMATAN_PASIEN,
                'kelurahan_pasien'              => $this->pa->kelurahan_pasien,
                'kota_pasien'                   => $this->pa->KOTA_PASIEN,
                'telp_asal'                     => $this->pa->TELP_ASAL,
                'alamat_disurabaya_pasien'      => $this->pa->ALAMAT_DISURABAYA_PASIEN,
                'kodepos_surabaya'              => $this->pa->KODEPOS_SURABAYA,
                'telp_pasien'                   => $this->pa->TELP_PASIEN,
                'hp_pasien'                     => $this->pa->HP_PASIEN,
                'e_mail_pasien'                 => $this->pa->E_MAIL_PASIEN,
                'instansi_pasien'               => $this->pa->INSTANSI_PASIEN,
                'alamat_kantor_pasien'          => $this->pa->ALAMAT_KANTOR_PASIEN,
                'kode_pos_kantor_pasien'        => $this->pa->KODE_POS_KANTOR_PASIEN,
                'telp_kantor_pasien'            => $this->pa->TELP_KANTOR_PASIEN,
                'nama_suami_pasien'             => $this->pa->NAMA_SUAMI_PASIEN,
                'pekerjaan_suami_pasien'        => $this->pa->PEKERJAAN_SUAMI_PASIEN,
                'nama_ayah_kandung_pasien'      => $this->pa->NAMA_AYAH_KANDUNG_PASIEN,
                'pekerjaan_ayah_kandung_pasien' => $this->pa->PEKERJAAN_AYAH_KANDUNG_PASIEN,
                'Jenis_pembayaran'              => $this->pa->Jenis_pembayaran,
                'penanggung_biaya_pasien'       => $this->pa->PENANGGUNG_BIAYA_PASIEN,
                'nama_ang_kel_pasien'           => $this->pa->NAMA_ANG_KEL_PASIEN,
                'alamat_ang_kel_pasien'         => $this->pa->ALAMAT_ANG_KEL_PASIEN,
                'kode_pos_ang_kel_pasien'       => $this->pa->KODE_POS_ANG_KEL_PASIEN,
                'telp_ang_kel_pasien'           => $this->pa->TELP_ANG_KEL_PASIEN,
                'hp_ang_kel_pasien'             => $this->pa->HP_ANG_KEL_PASIEN,
                'e_mail_ang_kel_pasien'         => $this->pa->E_MAIL_ANG_KEL_PASIEN,
                'hubungan_ang_kel_pasien'       => $this->pa->HUBUNGAN_ANG_KEL_PASIEN,
                'kodekota_pasien'               => $this->pa->KODEKOTA_PASIEN,
                'provinsi_pasien'               => $this->pa->PROVINSI_PASIEN,
                'pekerjaan_pasien'              => $this->pa->PEKERJAAN_PASIEN,
                'foto_pasien'                   => $this->pa->FOTO_PASIEN,
                'logtime_pegawai_entry_mr4'     => $this->pa->LOGTIME_PEGAWAI_ENTRY_MR4,
                'tanggal_daftar'                => $this->pa->TANGGAL_DAFTAR,
                'suku'                          => $this->pa->SUKU,
                'negara_pasien'                 => $this->pa->NEGARA_PASIEN,
                'rsos_brosur'                   => $this->pa->rsos_brosur,
                'rsos_news'                     => $this->pa->rsos_news,
                'rsos_health'                   => $this->pa->rsos_health,
                'company'                       => $this->pa->company,
                'rujukan'                       => $this->pa->rujukan,
                'internet'                      => $this->pa->internet,
                'keluarga'                      => $this->pa->keluarga,
                'rujukan_ket'                   => $this->pa->rujukan_ket,
                'internet_ket'                  => $this->pa->internet_ket,
                'keluarga_ket'                  => $this->pa->keluarga_ket,
                'others'                        => $this->pa->others,
                'rsos_health_ket'               => $this->pa->rsos_health_ket,
                'company_ket'                   => $this->pa->company_ket,
                'others_ket'                    => $this->pa->others_ket,
                'Status_BC'                     => $this->pa->Status_BC,
                'tgl_bc'                        => $this->pa->tgl_bc,
                'status_dead'                   => $this->pa->status_dead,
                'tgl_dead'                      => $this->pa->tgl_dead,
                'status_drm_keluar'             => $this->pa->status_drm_keluar,
                'time_add'                      => $this->pa->time_add,
                'time_fin'                      => $this->pa->time_fin,
                'nama_ibu'                      => $this->pa->NAMA_IBU,
                'flag_daftar'                   => $this->pa->flag_daftar,
                'log_start'                     => $this->pa->log_start,
                'log_stop'                      => $this->pa->log_stop,
                'bahasa_pasien'                 => $this->pa->bahasa_pasien,
                'penerjemah'                    => $this->pa->penerjemah,
                'retensi'                       => $this->pa->retensi,
                'status_hsl'                    => $this->pa->status_hsl,
                'comment'                       => $this->pa->comment,
                'instansi_suami'                => $this->pa->instansi_suami,
                'instansi_ayah'                 => $this->pa->instansi_ayah,
                'meninggal_ket'                 => $this->pa->meninggal_ket,
            ]);

            \DB::commit();
            
            return $pa;
            // return $pasien;testing

        }catch(\Exception $e){
            \DB::rollback();

            return [
                'error'  => $e->getMessage()
            ];
        }

        
    }

}