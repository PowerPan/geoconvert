<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 18.05.13
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */
include_once("GPS.php");
include_once("UTM.php");
include_once("UTMREF.php");

class geoconvert {

    private $utm;
    private $gps_dezi;
    private $gps_bogen;
    private $gps_bogen_sek;
    private $utmref;

    private $a = 6378137; //WGS84
    private $e2 = 0.00669437999013;

    // N/S = Y-Achse
    // O/W = X-Achse

    public function set_gps_dezi($lat,$lng){
        $gps = new GPS();
        $gps->set_latlng($lat,$lng);
    }

    public function get_gps_dezi(){
        return $this->gps_dezi['x']." ".$this->gps_dezi['y'];
    }

    public function get_gps_bogen(){
        return $this->gps_bogen['x']['grad']."° ".$this->gps_bogen['x']['minute']."' ".$this->gps_bogen['y']['grad']."° ".$this->gps_bogen['y']['minute']."' ";
    }

    public function get_gps_bogen_sek(){
        return $this->gps_bogen_sek['x']['grad']."° ".$this->gps_bogen_sek['x']['minute']."' ".$this->gps_bogen_sek['x']['sekunde']."'' ".$this->gps_bogen_sek['y']['grad']."° ".$this->gps_bogen_sek['y']['minute']."' ".$this->gps_bogen_sek['y']['sekunde']."'' ";
    }

    public function get_utm(){
        return $this->utm['zone']." ".$this->utm['x']." ".$this->utm['y'];
    }





}