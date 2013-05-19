<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 18.05.13
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */

class geoconvert {

    private $utm;
    private $gps_dezi;
    private $gps_bogen;
    private $gps_bogen_sek;
    private $utmref;

    private $a = 6378137; //WGS84

    // N/S = Y-Achse
    // O/W = X-Achse

    public function set_gps_dezi($lat,$lng){
        $this->gps_dezi['x'] = $lat;
        $this->gps_dezi['y'] = $lng;
        $this->gps_dezi2bogen();
        $this->gps_bogen2bogen_sek();
        $this->utm_zone();

        $this->gps_bogen_sek2bogen();
        $this->gps_bogen2dezi();
    }

    public function get_gps_dezi(){
        return $this->gps_dezi;
    }

    public function get_gps_bogen(){
        return $this->gps_bogen;
    }

    public function get_gps_bogen_sek(){
        return $this->gps_bogen_sek;
    }

    public function get_utm(){
        return $this->utm;
    }

    private function gps_dezi2bogen(){
        //Umrechnung von Dezimal dd.ddddd nach dd mm.mmm
        //X-Achse
        $komma = explode(".",$this->gps_dezi['x']);
        $mm = ($this->gps_dezi['x']-$komma[0])*60;

        $this->gps_bogen['x']['grad'] = $komma[0];
        $this->gps_bogen['x']['minute'] = $mm;

        //Y-Achse
        $komma = explode(".",$this->gps_dezi['y']);
        $mm = ($this->gps_dezi['y']-$komma[0])*60;

        $this->gps_bogen['y']['grad'] = $komma[0];
        $this->gps_bogen['y']['minute'] = $mm;
    }

    private function gps_bogen2bogen_sek(){
        //Umrechnung von dd mm.mmm nach dd mm ss.s
        //X-Achse
        $komma = explode(".",$this->gps_bogen['x']['minute']);
        $ss = ($this->gps_bogen['x']['minute']-$komma[0])*60;

        $this->gps_bogen_sek['x']['grad'] = $this->gps_bogen['x']['grad'];
        $this->gps_bogen_sek['x']['minute'] = $komma[0];
        $this->gps_bogen_sek['x']['sekunde'] = $ss;

        //Y-Achse
        $komma = explode(".",$this->gps_bogen['y']['minute']);
        $ss = ($this->gps_bogen['y']['minute']-$komma[0])*60;

        $this->gps_bogen_sek['y']['grad'] = $this->gps_bogen['y']['grad'];
        $this->gps_bogen_sek['y']['minute'] = $komma[0];
        $this->gps_bogen_sek['y']['sekunde'] = $ss;
    }

    private function gps_bogen2dezi(){
        //Umrechnung von Dezimal dd mm.mmm nach dd.ddddd
        //X-Achse
        $this->gps_dezi['x'] = $this->gps_bogen['x']['grad'] + ($this->gps_bogen['x']['minute']/60);

        //Y-Achse
        $this->gps_dezi['y'] = $this->gps_bogen['y']['grad'] + ($this->gps_bogen['y']['minute']/60);
    }

    private function gps_bogen_sek2bogen(){
        //Umrechnung von dd mm ss.s nach dd mm.mmm
        //X-Achse
        $this->gps_bogen['x']['grad'] = $this->gps_bogen_sek['x']['grad'];
        $this->gps_bogen['x']['minute'] = $this->gps_bogen_sek['x']['minute'] + ($this->gps_bogen_sek['x']['sekunde']/60);

        //Y-Achse
        $this->gps_bogen['y']['grad'] = $this->gps_bogen_sek['y']['grad'];
        $this->gps_bogen['y']['minute'] = $this->gps_bogen_sek['y']['minute'] + ($this->gps_bogen_sek['y']['sekunde']/60);
    }

    private function utm_zone(){
        //http://www.anuva.de/service_arcforum.php?action=vthread&forum=3&topic=1559
        $lat = intval(($this->gps_dezi['x']+80)/8)+1;
        $lng = intval(($this->gps_dezi['y']+180)/6)+1;

        switch($lat){
            case 1: $zone = "C"; break;
            case 2: $zone = "D"; break;
            case 3: $zone = "E"; break;
            case 4: $zone = "F"; break;
            case 5: $zone = "G"; break;
            case 6: $zone = "H"; break;
            case 7: $zone = "J"; break;
            case 8: $zone = "K"; break;
            case 9: $zone = "L"; break;
            case 10: $zone = "M"; break;
            case 11: $zone = "N"; break;
            case 12: $zone = "P"; break;
            case 13: $zone = "Q"; break;
            case 14: $zone = "R"; break;
            case 15: $zone = "S"; break;
            case 16: $zone = "T"; break;
            case 17: $zone = "U"; break;
            case 18: $zone = "V"; break;
            case 19: $zone = "W"; break;
            case 20: $zone = "X"; break;
            case 21: $zone = "X"; break;
        }
        $this->utm['zone'] = $lng.$zone;
    }

    private function gps_dezi2utm(){
        //http://www.mydarc.de/dh2mic/files/utm.pdf

        $lat_rad = DegToRad($this->gps_dezi['x']);
        $lng_rad = DegToRad($this->gps_dezi['y']);

        $ko = 0.9996;
        $E = pow(M_E,2)/(1-pow(M_E,2));
        $N = $this->a / pow(1-pow(M_E,2)*pow(sin($lat_rad),2),0.5);
        $T = pow(tan($lat_rad),2);
        $C = $E * pow(tan($lat_rad),2);
    }

    private function DegToRad($deg){
        return ($deg/180.0 * M_PI);

    }

}