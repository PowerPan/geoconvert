<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class GPS {
    protected  $lat_grad;
    protected $lng_grad;

    public function set_latlng_dezi($lat,$lng){
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function get_lat(){
        return $this->lat_grad;
    }

    public function get_lng(){
        return $this->lng_grad;
    }

    public function set_latlng_dezi_from_bogen_sek($lat_grad,$lat_minute,$lat_sekunde,$lng_grad,$lng_minute,$lng_sekunde){
        $this->lat_grad = $lat_grad + ($lat_minute + ($lat_sekunde/60)/60);
        $this->lng_grad = $lng_grad + ($lng_minute + ($lng_sekunde/60)/60);
    }

    public function set_latlng_dezi_from_bogen($lat_grad,$lat_minute,$lng_grad,$lng_minute){
        $this->lat_grad = $lat_grad + ($lat_minute/60);
        $this->lng_grad = $lng_grad + ($lng_minute/60);
    }

}