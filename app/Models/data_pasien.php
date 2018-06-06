<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_pasien extends Model
{
    protected $table = 'data_pasien';
    protected $primaryKey 	= 'id_pasien';
	protected $fillable 	= [
		'id_pasien',
		'id_pasien_hc',
		'id_perpenjamin',
		'id_layanan_rs',
		'id_pgw',
		'nama_pasien',
		'noktp_pasien',
		'noasuransi_pasien',
		'jk_pasien',
		'status_nikah_pasien',
		'tgllahir_pasien',
		'tempatlahir_pasien',
		'agama_pasien',
		'warga_negara_pasien',
		'pendidikan_pasien',
		'alamat_pasien',
		'kodepos_pasien',
		'kecamatan_pasien',
		'kelurahan_pasien',
		'kota_pasien',
		'telp_asal',
		'alamat_disurabaya_pasien',
		'kodepos_surabaya',
		'telp_pasien',
		'hp_pasien',
		'e_mail_pasien',
		'instansi_pasien',
		'alamat_kantor_pasien',
		'kode_pos_kantor_pasien',
		'telp_kantor_pasien',
		'nama_suami_pasien',
		'pekerjaan_suami_pasien',
		'nama_ayah_kandung_pasien',
		'pekerjaan_ayah_kandung_pasien',
		'Jenis_pembayaran',
		'penanggung_biaya_pasien',
		'nama_ang_kel_pasien',
		'alamat_ang_kel_pasien',
		'kode_pos_ang_kel_pasien',
		'telp_ang_kel_pasien',
		'hp_ang_kel_pasien',
		'e_mail_ang_kel_pasien',
		'hubungan_ang_kel_pasien',
		'kodekota_pasien',
		'provinsi_pasien',
		'pekerjaan_pasien',
		'foto_pasien',
		'logtime_pegawai_entry_mr4',
		'tanggal_daftar',
		'suku',
		'negara_pasien',
		'rsos_brosur',
		'rsos_news',
		'rsos_health',
		'company',
		'rujukan',
		'internet',
		'keluarga',
		'rujukan_ket',
		'internet_ket',
		'keluarga_ket',
		'others',
		'rsos_health_ket',
		'company_ket',
		'others_ket',
		'Status_BC',
		'tgl_bc',
		'status_dead',
		'tgl_dead',
		'status_drm_keluar',
		'time_add',
		'time_fin',
		'nama_ibu',
		'flag_daftar',
		'log_start',
		'log_stop',
		'bahasa_pasien',
		'penerjemah',
		'retensi',
		'status_hsl',
		'comment',
		'instansi_suami',
		'instansi_ayah',
		'tipe', //1 inpt via hc 2, input via Sim
		'meninggal_ket'
	];
	
	public function scopeListbiling($query){
		return $query->join('data_faktur', 'data_faktur.id_pasien', '=', 'data_pasien.id_pasien_hc')
			->join('data_jurnal', 'data_jurnal.id_faktur', '=', 'data_faktur.id_faktur')
			->groupby('data_pasien.id_pasien_hc')
			->select('data_pasien.*');
	}

}
