<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class UTM extends GPS{
    protected  $zone;
    protected $easting;
    protected $northing;
    protected $bezugsmeridian;


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
        $this->calculate_from_dezi();
    }

    public function set_utm($zone,$easting,$northing){
        $this->$zone = $zone;
        $this->easting = $easting;
        $this->northing = $northing;
        $this->calculate_to_dezi();
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

        $G1 = 13 * pow($C,2) + 4 * pow($C,3) - 64 * pow($C,2)*$T - 24 * pow($C,3) * $T;
        $G2 = (61 - 479 * $T + 179 * pow($T,2) - pow($T,3))*pow($A,7)/5040;
        $G3 = 445 * pow($C,2) + 324 * pow($C,3) - 680 * pow($C,2) * $T + 88 * pow($C,4) - 600 * pow($C,3) * $T - 192 * pow($C,4) * $T;
        $G4 = (1385 - 3111 * $T + 543 * pow($T,2) - pow($T,3) ) * pow($A,8) / 40320;

        $this->easting = intval($k0*$N*($A+(1-$T+$C)*$A*$A*$A/6 + (5-18*$T+$T*$T+72*$C-58*$E + $G1)*$A*$A*$A*$A*$A/120 + $G2) + 500000.0);
        $this->northing = intval($k0*($M+$N*tan($lat_rad)*($A*$A/2+(5-$T+9*$C+4*$C*$C)*$A*$A*$A*$A/24 + (61-58*$T+$T*$T+600*$C-330*$E + $G3)*$A*$A*$A*$A*$A*$A/720))+$G4);
    }

    private function find_bezugsmeridian(){
        $this->bezugsmeridian = (substr($this->zone,0,2) - 1)*6 - 180 + 3;
    }

    private function calculate_to_dezi(){

        $this->find_bezugsmeridian();

        $k0 = 0.9996;
        $e2 = pow(sqrt(pow($this->a,2)-pow($this->b,2))/$this->b,2);
        $E1 = (1 - sqrt(1 - $e2) )/(1 + sqrt(1 - $e2) ) ;

        $x = $this->easting-500000.0;
        $y = $this->northing;

        $eccSquared = $e2;
        $e1 = (1-sqrt(1-$eccSquared))/(1+sqrt(1-$eccSquared));
        $eccPrimeSquared = ($eccSquared)/(1-$eccSquared);

        $M = $y / $k0;
        $mu = $M/($this->a*(1-$eccSquared/4-3*$eccSquared*$eccSquared/64-5*$eccSquared*$eccSquared*$eccSquared/256));

        $phi1Rad = $mu	+ (3*$e1/2-27*$e1*$e1*$e1/32)*sin(2*$mu)
            + (21*$e1*$e1/16-55*$e1*$e1*$e1*$e1/32)*sin(4*$mu)
            +(151*$e1*$e1*$e1/96)*sin(6*$mu);
        $phi1 = $this->RadToDeg($phi1Rad);

        $N1 = $this->a/sqrt(1-$eccSquared*sin($phi1Rad)*sin($phi1Rad));
        $T1 = tan($phi1Rad)*tan($phi1Rad);
        $C1 = $eccPrimeSquared*cos($phi1Rad)*cos($phi1Rad);
        $R1 = $this->a*(1-$eccSquared)/pow(1-$eccSquared*sin($phi1Rad)*sin($phi1Rad), 1.5);
        $D = $x/($N1*$k0);

        $Lat = $phi1Rad - ($N1*tan($phi1Rad)/$R1)*($D*$D/2-(5+3*$T1+10*$C1-4*$C1*$C1-9*$eccPrimeSquared)*$D*$D*$D*$D/24
            +(61+90*$T1+298*$C1+45*$T1*$T1-252*$eccPrimeSquared-3*$C1*$C1)*$D*$D*$D*$D*$D*$D/720);
        $Lat = $this->RadToDeg($Lat);

        $Long = ($D-(1+2*$T1+$C1)*$D*$D*$D/6+(5-2*$C1+28*$T1-3*$C1*$C1+8*$eccPrimeSquared+24*$T1*$T1)
            *$D*$D*$D*$D*$D/120)/cos($phi1Rad);
        $Long = $this->bezugsmeridian + $this->RadToDeg($Long);

        $lat = $Lat;
        $lng = $Long;

        /*
        $k0 = 0.9996;
        $e2 = pow(sqrt(pow($this->a,2)-pow($this->b,2))/$this->b,2);
        $E1 = (1 - sqrt(1 - $e2) )/(1 + sqrt(1 - $e2) ) ;
        $M1 = $this->northing / $k0;
        $MU = $M1 / ( 1 - $e2/4 - 3*$e2*$e2/64 - 5*$e2*$e2*$e2/256 );
        $phi1 = $MU + (3*$E1/2 - 27*$E1*$E1*$E1/32)*sin(2*$MU) + ( 32*pow($E1,2)/16 - 55 * pow($E1,4)/32  ) + sin(4*$MU) + (151*pow($E1,3)/96  ) * sin(6*$MU) + (1097*pow($E1,4)/512)*sin(8*$MU);
        $E = $e2/(1-$e2);
        $C1 = $E * pow(cos($phi1),2);
        $T1 = pow(tan($phi1),2);
        $N1 = $this->a / sqrt(1 - $e2 * pow(sin($phi1),2));
        $R1 = ( $this->a * (1 - $e2) ) / pow((1 - $e2 * pow(sin($phi1),2)),3/2);
        $D = ($this->easting-500000.0) / ($N1 * $k0);

        $G5 = -90 * $C1 * pow($T1,2) - 66 * pow($C1,2) * $T1 + 255 * pow($C1,2) * pow($T1,2) + 100 * pow($C1,3) +84 * pow($C1,3) * $T1 + 88 * pow($C1,4) - 192 * pow($C1,4) * $T1;
        $G6 = - ( 1385 + 3633 * $T1 + 4095 * pow($T1,2) + 1574 * pow($T1,3))*pow($D,8)/40320;
        $G7 = - 4 * pow($C1,3) + 4 * pow($C1,2) * $T1 + 24 * pow($C1,3) * $T1;
        $G8 = - ( 61 + 662 * $T1 + 1320 * pow($T1,2) + 720* pow($T1,3) )*pow($D,7)/5040;

        $lat = $phi1 - ( $N1/($R1*tan($phi1))) * ( pow($D,2)/2 - ( 5 + 3 * $T1 + 10 * $C1 - 4 * pow($C1,2) - 9 * $E ) * pow($D,4)/24 + ( 61 + 90 * $T1 + 298 * $C1 + 45 * pow($T1,2) -252 * $E - 3 * pow($C1,2) + $G5) * pow($D,6)/720 + $G6   );
        $lng = ( $D - ( 1 + 2 * $T1 + $C1 )/(6*pow($D,3))  +  ( 5 - 2 * $C1 + 28 * $T1 - 3 * pow($C1,2) + 8 * $E + 24 * pow($T1,2) + $G7  ) * pow($D,5)/120 + $G8  )/ cos($phi1);
        */
        $this->set_latlng_dezi($this->RadToDeg($lat),$this->RadToDeg($lng));

    }

    private function DegToRad($deg){
        return ($deg/180.0 * M_PI);
    }

    private function RadToDeg($rad){
        return ($rad/M_PI) * 180.0;
    }
}