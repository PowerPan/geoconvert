<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class UTM {
    private $zone;
    private $easting;
    private $northing;

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

        //Berechung von UTM Zone und Berzugsmeridian
        $this->utm_zone();
        $this->utm_bezugsmeridian();

        $ko = 0.9996;
        $E = pow($this->e2,2)/(1-pow($this->e2,2));
        $N = $this->a / pow(1-pow($this->e2,2)*pow(sin($this->gps_dezi['y']),2),0.5);
        $T = pow(tan($this->gps_dezi['y']),2);
        $C = $E * pow(tan($this->gps_dezi['y']),2);
        $A = $this->DegToRad(($this->gps_dezi['x']-$this->utm['bezugsmeridian'])) * cos($this->gps_dezi['y']);

        // Die Reihe hab ich noch nicht durchblickt und daher stumpf abgechrieben JJR 19.05.2013
        $M = $this->a * (   ((1- (pow($this->e2,2)/4) - 3*(pow($this->e2,4)/64) - 5*(pow($this->e2,6)/256))*$this->gps_dezi['y'])
            -( (3*(pow($this->e2,2)/8) + 3*(pow($this->e2,4)/32) + 45*(pow($this->e2,6)/1024))*sin(2*$this->gps_dezi['y']))
            +( (15*(pow($this->e2,4)/256) + 45*(pow($this->e2,6)/1024))*sin(4*$this->gps_dezi['y']) )
            -( (35*(pow($this->e2,6)/3072))*sin(6*$this->gps_dezi['x']))
        );

        $G1 = 13 * pow($C,2) + 4 * pow($C,3) - 64 * pow($C,2)*$T - 24 * pow($C,3) * $T;
        $G2 = (61 - 479 * $T + 179 * pow($T,2) - pow($T,3))*pow($A,7)/5040;
        $G3 = 445 * pow($C,2) + 324 * pow($C,3) - 680 * pow($C,2) * $T + 88 * pow($C,4) - 600 * pow($C,3) * $T - 192 * pow($C,4) * $T;
        $G4 = (1385 - 3111 * $T + 543 * pow($T,2) - pow($T,3) ) * pow($A,8) / 40320;


        $this->utm['x'] = $ko * $N * ( $A + (1 - $T + $C) * pow($A,3)/6 + (5 - 18 * $T + pow($T,2) + 72 * $C - 58 * $E + $G1 ) * pow($A,5)/120 + $G2 );
        $this->utm['y'] = $ko * ( $M + $N * tan($this->DegToRad($this->gps_dezi['y'])) * ( pow($A,2)/2 + ( 5 - $T + 9 * $C + 4 * pow($C,2)) * pow($A,4)/24 + ( 61 - 58 * $T + pow($T,2) + 600 * $C - 330 * $E + $G3 ) * pow($A,6)/720  )  + $G4   );
    }

    private function utm_bezugsmeridian(){
        $this->utm['bezugsmeridian'] = (-183.0 + ($this->utm['zone'] * 6.0));
    }

    private function DegToRad($deg){
        return ($deg/180.0 * M_PI);

    }
}