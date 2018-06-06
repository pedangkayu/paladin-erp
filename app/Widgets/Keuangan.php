<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class Keuangan extends AbstractWidget {
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
      'levels' => [ // <----------- Akses level (Default)
        1, // Super admon
        2, // admin
        76 // Keuangan(basic)
      ]
    ];

    // Pengesetan akses ke widget ini
    public function __construct(){
      $this->config['check'] = $this->check();
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run() {

      $data['config'] = $this->config;
        return view("widgets.keuangan", $data);
    }

    // Melakukan pengecekan terhadap level yang sedang login
    public function check(){
      $status = [];
      // Mengurai level yang login
      foreach(\Me::level() as $lev){
        // Jika level yang login ada kesamaan dengan akses level yang sudah ditentukan
        if(in_array($lev, $this->config['levels']))
          $status[] = true; // set true jika ditemukan
        else
          $status[] = false; // set false jika tidak ditemukan
      }
      return $status; // Array
    }
}
