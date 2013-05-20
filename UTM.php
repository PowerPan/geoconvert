<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class UTM extends GPS{
    private $zone;
    private $easting;
    private $northing;
    private $bezugsmeridian;


    private $a = 6378137; //WGS84
    private $b = 6356752.3142;
    

    public function get_zone(){
        return $this->zone;
    }

    public function get_easting(){
        return $this->easting;
    }

    public function  get_northing(){
        return $this->northing;
    }

    public function set_latlng_dezi($lat,$lng){
        parent::set_latlng_dezi($lat,$lng);
        $this->find_zone();
        $this->calculate_from_dezi();
    }

    private function find_zone(){
        //http://www.anuva.de/service_arcforum.php?action=vthread&forum=3&topic=1559
        $lat = intval(($this->lat_grad+80)/8)+1;
        $lng = intval(($this->lng_grad+180)/6)+1;

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
        $this->zone = $lng.$zone;
    }

    private function calculate_from_dezi(){
        //http://www.mydarc.de/dh2mic/files/utm.pdf
        //http://www.gpsy.com/gpsinfo/geotoutm/gantz/LatLong-UTMconversion.cpp.txt

        //Berechung von UTM Zone und Berzugsmeridian
        $this->find_zone();
        $this->find_bezugsmeridian();
        $lat_rad = $this->DegToRad($this->lat_grad);
        $lng_rad = $this->DegToRad($this->lng_grad);

        $e2 = pow(sqrt(pow($this->a,2)-pow($this->b,2))/$this->b,2);

        $k0 = 0.9996;
        $E = $e2/(1-$e2);
        $N = $this->a / sqrt(1-$e2*sin($lat_rad)*sin($lat_rad));
        $T = pow(tan($lat_rad),2);
        $C = $E * pow(cos($lat_rad),2);
        $A = cos($lat_rad) * ($lng_rad - $this->DegToRad($this->bezugsmeridian) );

        $M = $this->a*((1	- $e2/4		- 3*$e2*$e2/64	- 5*$e2*$e2*$e2/256)*$lat_rad
            - (3*$e2/8	+ 3*$e2*$e2/32	+ 45*$e2*$e2*$e2/1024)*sin(2*$lat_rad)
            + (15*$e2*$e2/256 + 45*$e2*$e2*$e2/1024)*sin(4*$lat_rad)
            - (35*$e2*$e2*$e2/3072)*sin(6*$lat_rad));
        
        // Die Reihe hab ich noch nicht durchblickt und daher stumpf abgechrieben JJR 19.05.2013
        /*$M = $this->a * (   ((1- (pow($e2,1)/4) - 3*(pow($e2,2)/64) - 5*(pow($e2,3)/256))*$lat_rad
            -( (3*(pow($e2,1)/8) + 3*(pow($e2,2)/32) + 45*(pow($e2,3)/1024))*sin(2*$lat_rad))
            +( (15*(pow($e2,2)/256) + 45*(pow($e2,3)/1024))*sin(4*$lat_rad) )
            -( (35*(pow($e2,3)/3072))*sin(6*$lat_rad))
        ));*/

        $this->easting = intval($k0*$N*($A+(1-$T+$C)*$A*$A*$A/6 + (5-18*$T+$T*$T+72*$C-58*$E)*$A*$A*$A*$A*$A/120) + 500000.0);
        $this->northing = intval($k0*($M+$N*tan($lat_rad)*($A*$A/2+(5-$T+9*$C+4*$C*$C)*$A*$A*$A*$A/24 + (61-58*$T+$T*$T+600*$C-330*$E)*$A*$A*$A*$A*$A*$A/720)));

        /*$G1 = 13 * pow($C,2) + 4 * pow($C,3) - 64 * pow($C,2)*$T - 24 * pow($C,3) * $T;
        $G2 = (61 - 479 * $T + 179 * pow($T,2) - pow($T,3))*pow($A,7)/5040;
        $G3 = 445 * pow($C,2) + 324 * pow($C,3) - 680 * pow($C,2) * $T + 88 * pow($C,4) - 600 * pow($C,3) * $T - 192 * pow($C,4) * $T;
        $G4 = (1385 - 3111 * $T + 543 * pow($T,2) - pow($T,3) ) * pow($A,8) / 40320;*/


        //$this->easting = $ko * $N * ( $A + (1 - $T + $C) * pow($A,3)/6 + (5 - 18 * $T + pow($T,2) + 72 * $C - 58 * $E + $G1 ) * pow($A,5)/120 + $G2 );
        //$this->northing = $ko * ( $M + $N * tan($this->DegToRad($this->gps_dezi['y'])) * ( pow($A,2)/2 + ( 5 - $T + 9 * $C + 4 * pow($C,2)) * pow($A,4)/24 + ( 61 - 58 * $T + pow($T,2) + 600 * $C - 330 * $E + $G3 ) * pow($A,6)/720  )  + $G4   );
    }

    private function find_bezugsmeridian(){
        $this->bezugsmeridian = ($this->zone - 1)*6 - 180 + 3;
    }

    private function DegToRad($deg){
        return ($deg/180.0 * M_PI);

    }
}