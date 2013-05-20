<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 18.05.13
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */
include_once("GPS.php");
include_once("GPSBogen.php");
include_once("GPSBogenSek.php");
include_once("UTM.php");
include_once("UTMREF.php");

class geoconvert {

    private $utm;
    private $gps_dezi;
    private $gps_bogen;
    private $gps_bogen_sek;
    private $utmref;



    // N/S = Y-Achse
    // O/W = X-Achse

    public function __construct(){
        $this->gps_dezi = new GPS();
        $this->gps_bogen = new GPSBogen();
        $this->gps_bogen_sek = new GPSBogenSek();
    }

    public function set_gps_dezi($lat,$lng){
        $this->gps_dezi->set_latlng_dezi($lat,$lng);
        $this->gps_bogen->set_latlng_bogen_from_dezi($lat,$lng);
        $this->gps_bogen_sek->set_latlng_bogen_sek_from_dezi($lat,$lng);
    }

    public function get_gps_dezi(){
        return $this->gps_dezi->get_lat_grad()." ".$this->gps_dezi->get_lng_grad();
    }

    public function get_gps_bogen(){
        return $this->gps_bogen->get_lat_grad()."° ".$this->gps_bogen->get_lat_minute()."' ".$this->gps_bogen->get_lng_grad()."° ".$this->gps_bogen->get_lng_minute()."' ";
    }

    public function get_gps_bogen_sek(){
        return $this->gps_bogen_sek->get_lat_grad()."° ".$this->gps_bogen_sek->get_lat_minute()."' ".$this->gps_bogen_sek->get_lat_sekunde()."'' ".$this->gps_bogen_sek->get_lng_grad()."° ".$this->gps_bogen_sek->get_lng_minute()."' ".$this->gps_bogen_sek->get_lng_sekunde()."'' ";
    }

    public function get_utm(){
        return $this->utm['zone']." ".$this->utm['x']." ".$this->utm['y'];
    }





}