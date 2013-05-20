<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 20.05.13
 * Time: 09:31
 * To change this template use File | Settings | File Templates.
 */

class GPSBogen extends GPS{
    protected $lat_minute;
    protected $lng_minute;

    public function set_latlng_bogen_from_dezi($lat,$lng){
        $this->set_latlng_dezi($lat,$lng);

        //Umrechnung von Dezimal dd.ddddd nach dd mm.mmm
        //X-Achse
        $komma = explode(".",$this->lat_grad);
        $mm = ($this->lat_grad-$komma[0])*60;

        $this->lat_grad = $komma[0];
        $this->lat_minute = $mm;

        //Y-Achse
        $komma = explode(".",$this->lng_grad);
        $mm = ($this->lng_grad-$komma[0])*60;

        $this->lng_grad = $komma[0];
        $this->lng_minute = $mm;
    }

    public function get_lat_minute(){
        return $this->lat_minute;
    }

    public function get_lng_minute(){
        return $this->lng_minute;
    }

    public function set_latlng_bogen($lat_grad,$lat_minute,$lng_grad,$lng_minute){
        $this->lat_grad = $lat_grad;
        $this->lat_minute = $lat_minute;

        $this->lng_grad = $lng_grad;
        $this->lng_minute = $lng_minute;
    }

    public function set_latlng_bogen_from_bogen_sek($lat_grad,$lat_minute,$lat_sekunde,$lng_grad,$lng_minute,$lng_sekunde){
        $this->lat_grad = $lat_grad;
        $this->lat_minute = $lat_minute + ($lat_sekunde/60);

        $this->lng_grad = $lng_grad;
        $this->lng_minute = $lng_minute + ($lng_sekunde/60);
    }

}