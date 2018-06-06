<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EditLoginController extends Controller
{
   public function getIndex(Request $req){

        if(\Auth::check())
            return redirect('/treatment/viewhc/' .$req->id_pasien.'/'.$req->id_layanan_rs.'/'.$req->antrian);  /* Redirect ini harus sama dengan yang di bawah */

        $user = User::whereUsername($req->username)->first();
        
        \Auth::loginUsingId($user->id_user);

        
        if(\Auth::check())
            return redirect('/treatment/viewhc/' .$req->id_pasien.'/'.$req->id_layanan_rs.'/'.$req->antrian); /* Redirect ini harus sama dengan yang di atas */

    }
    public function getLogout(Request $req)
         {
         
         \Auth::logout();
         
         return view('Pelayanan.Treatment.clode');
         }
}
