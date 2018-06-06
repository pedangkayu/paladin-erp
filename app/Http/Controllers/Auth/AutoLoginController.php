<?php

namespace App\Http\Controllers\Auth;

use App\User;

use Illuminate\Http\Request;
use App\Jobs\Antrian\InsertAntrianJob;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AutoLoginController extends Controller {
    
    public function getIndex(Request $req){

        if(\Auth::check())
            return redirect('/treatment/create?id_pasien=' .$req->id_pasien.'&thn_antrian=' .$req->thn_antrian . '&bln_antrian=' .$req->bln_antrian. '&tgl_antrian=' .$req->tgl_antrian .'&id_antrian=' .$req->id_antrian . '&id_dokter=' . $req->id_dokter.'&id_layanan_rs='.$req->id_layanan_rs.'&antrian='.$req->antrian );  /* Redirect ini harus sama dengan yang di bawah */

         $user = User::whereUsername($req->username)->first();
        
        \Auth::loginUsingId($user->id_user);

        
        if(\Auth::check())
            return redirect('/treatment/create?id_pasien=' .$req->id_pasien.'&thn_antrian=' .$req->thn_antrian . '&bln_antrian=' .$req->bln_antrian. '&tgl_antrian=' .$req->tgl_antrian .'&id_antrian=' .$req->id_antrian . '&id_dokter=' . $req->id_dokter.'&id_layanan_rs='.$req->id_layanan_rs.'&antrian='.$req->antrian); /* Redirect ini harus sama dengan yang di atas */
            // return redirect('/treatment/create?id_pasien=' .$req->id_pasien.'&thn_antrian=' .$req->thn_antrian . '&bln_antrian=' .$req->bln_antrian. '&tgl_antrian=' .$req->tgl_antrian .'&id_antrian=' .$req->id_antrian . '&id_dokter=' . $req->id_dokter); /* Redirect ini harus sama dengan yang di atas */
        
    }
    public function getLogout(Request $req)
         {
         
         \Auth::logout();
         
         return view('Pelayanan.Treatment.clode');
         }

}
