<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service_tindakan extends Model
{
     protected $table = 'ref_service_tindakan';
	protected $primaryKey = 'id_tindakan';
	protected $fillable = [
			'id_tindakan',
			'grup',
			'tindakan',
		];

 public function scopePakettindakan($query, $req = []){
		if(!empty($req['tindakan']))
			$query->where('ref_service_tindakan.tindakan','LIKE', '%' . $req['tindakan'] . '%');
			$query->select(
			'ref_service_tindakan.*'
		);
		}


}
