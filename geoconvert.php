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
include_once("Geocoder.php");

class geoconvert {

    private $utm;
    private $gps_dezi;
    private $gps_bogen;
    private $gps_bogen_sek;
    private $utmref;
    private $geocoder;



    // N/S = Y-Achse
    // O/W = X-Achse

    public function __construct(){
        $this->gps_dezi = new GPS();
        $this->gps_bogen = new GPSBogen();
        $this->gps_bogen_sek = new GPSBogenSek();
        $this->utm = new UTM();
        $this->utmref = new UTMREF();
        $this->geocoder = new Geocoder();
    }

    public function set_gps_dezi($lat,$lng){
        $this->gps_dezi->set_latlng_dezi($lat,$lng);
        $this->gps_bogen->set_latlng_bogen_from_dezi($lat,$lng);
        $this->gps_bogen_sek->set_latlng_bogen_sek_from_dezi($lat,$lng);
        $this->utm->set_latlng_dezi($lat,$lng);
        $this->utmref->set_latlng_dezi($lat,$lng);
        $this->geocoder->set_latlng_dezi($lat,$lng);
    }

    public function set_utm($zone,$easting,$northing){
        $this->utm->set_utm($zone,$easting,$northing);
        $this->gps_dezi->set_latlng_dezi($this->utm->get_lat_grad(),$this->utm->get_lng_grad());
        $this->gps_bogen->set_latlng_bogen_from_dezi($this->utm->get_lat_grad(),$this->utm->get_lng_grad());
        $this->gps_bogen_sek->set_latlng_bogen_sek_from_dezi($this->utm->get_lat_grad(),$this->utm->get_lng_grad());
        $this->utmref->set_latlng_dezi($this->utm->get_lat_grad(),$this->utm->get_lng_grad());
        $this->geocoder->set_latlng_dezi($this->utm->get_lat_grad(),$this->utm->get_lng_grad());

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
        return $this->utm->get_zone()." ".$this->utm->get_easting()." ".$this->utm->get_northing();
    }

    public function get_utmref(){
        return $this->utmref->get_zone()." ".$this->utmref->get_zonenfeld()." ".$this->utmref->get_easting()." ".$this->utmref->get_northing();
    }

    public function get_adresse(){
        return  $this->geocoder->get_addresse();
    }





}