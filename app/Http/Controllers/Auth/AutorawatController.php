<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AutorawatController extends Controller
{
     public function getIndex(Request $req){

        if(\Auth::check())
            return redirect('/Rawatinap/datapasien?id_pasien=' .$req->id_pasien.'&id_antrian=' .$req->id_antrian . '&jenis=' .$req->jenis .'&kamar=' .$req->kamar );  /* Redirect ini harus sama dengan yang di bawah */
       
        $user = User::whereUsername($req->username)->first();
        
        \Auth::loginUsingId($user->id_user);

        
        if(\Auth::check())
            return redirect('/Rawatinap/datapasien?id_pasien=' .$req->id_pasien.'&id_antrian=' .$req->id_antrian . '&jenis=' .$req->jenis .'&kamar=' .$req->kamar );  /* Redirect ini harus sama dengan yang di atas */

    }
    public function getLogout(Request $req)
         {
         
         \Auth::logout();
         
         return view('Pelayanan.Treatment.clode');
         }

    }

    

