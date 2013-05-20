<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 20.05.13
 * Time: 09:32
 * To change this template use File | Settings | File Templates.
 */

class GPSBogenSek extends GPSBogen{
    protected $lat_sekunde;
    protected $lng_sekunde;

    public function set_latlng_bogen_sek($lat_grad,$lat_minute,$lat_sekunde,$lng_grad,$lng_minute,$lng_sekunde){
        $this->lat_grad = $lat_grad;
        $this->lat_minute = $lat_minute;
        $this->lat_sekunde = $lat_sekunde;

        $this->lng_grad = $lng_grad;
        $this->lng_minute = $lng_minute;
        $this->lng_sekunde = $lng_sekunde;
    }

    public function set_latlng_bogen_sek_from_bogen($lat_grad,$lat_minute,$lng_grad,$lng_minute){
        $this->set_latlng_bogen_from_bogen($lat_grad,$lat_minute,$lng_grad,$lng_minute);
        //Umrechnung von dd mm.mmm nach dd mm ss.s
        //X-Achse
        $komma = explode(".",$this->lat_minute);
        $ss = ($this->lat_minute-$komma[0])*60;

        $this->lat_minute = $komma[0];
        $this->lat_sekunde = $ss;

        //Y-Achse
        $komma = explode(".",$this->lng_minute);
        $ss = ($this->lng_minute-$komma[0])*60;

        $this->lng_minute = $komma[0];
        $this->lng_sekunde = $ss;
    }

    public function set_latlng_bogen_sek_from_dezi($lat,$lng){
        $this->set_latlng_bogen_from_dezi($lat,$lng);
        $this->set_latlng_bogen_sek_from_bogen($this->lat_grad,$this->lat_minute,$this->lng_grad,$this->lng_minute);
    }




}