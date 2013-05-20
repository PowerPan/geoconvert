<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

class UTMREF extends UTM{
    private $zonenfeld;

    public function set_latlng_dezi($lat,$lng){
        parent::set_latlng_dezi($lat,$lng);
        $this->calculate();
    }

    private function calculate(){
        //Zonenfeld finden
        //Zone durch 3 und den Rest begutachten

        $array_erster_buchstabe[1][1] = "A";
        $array_erster_buchstabe[1][2] = "B";
        $array_erster_buchstabe[1][3] = "C";
        $array_erster_buchstabe[1][4] = "D";
        $array_erster_buchstabe[1][5] = "E";
        $array_erster_buchstabe[1][6] = "F";
        $array_erster_buchstabe[1][7] = "G";
        $array_erster_buchstabe[1][8] = "H";
        $array_erster_buchstabe[2][1] = "J";
        $array_erster_buchstabe[2][2] = "K";
        $array_erster_buchstabe[2][3] = "L";
        $array_erster_buchstabe[2][4] = "M";
        $array_erster_buchstabe[2][5] = "N";
        $array_erster_buchstabe[2][6] = "P";
        $array_erster_buchstabe[2][7] = "Q";
        $array_erster_buchstabe[2][8] = "R";
        $array_erster_buchstabe[3][1] = "S";
        $array_erster_buchstabe[3][2] = "T";
        $array_erster_buchstabe[3][3] = "U";
        $array_erster_buchstabe[3][4] = "V";
        $array_erster_buchstabe[3][5] = "W";
        $array_erster_buchstabe[3][6] = "X";
        $array_erster_buchstabe[3][7] = "Y";
        $array_erster_buchstabe[3][8] = "Z";

        $rest = $this->zone%3;

        $erster_ziffer_easting = substr($this->easting,0,1);

        $erster_buchstabe = $array_erster_buchstabe[$rest][$erster_ziffer_easting];

        $array_zweiter_buchstabe[0][0][0] = "F";
        $array_zweiter_buchstabe[0][0][1] = "A";
        $array_zweiter_buchstabe[0][1][0] = "G";
        $array_zweiter_buchstabe[0][1][1] = "B";
        $array_zweiter_buchstabe[0][2][0] = "H";
        $array_zweiter_buchstabe[0][2][1] = "C";
        $array_zweiter_buchstabe[0][3][0] = "J";
        $array_zweiter_buchstabe[0][3][1] = "D";
        $array_zweiter_buchstabe[0][4][0] = "K";
        $array_zweiter_buchstabe[0][4][1] = "E";
        $array_zweiter_buchstabe[0][5][0] = "L";
        $array_zweiter_buchstabe[0][5][1] = "F";
        $array_zweiter_buchstabe[0][6][0] = "M";
        $array_zweiter_buchstabe[0][6][1] = "G";
        $array_zweiter_buchstabe[0][7][0] = "N";
        $array_zweiter_buchstabe[0][7][1] = "H";
        $array_zweiter_buchstabe[0][8][0] = "P";
        $array_zweiter_buchstabe[0][8][1] = "J";
        $array_zweiter_buchstabe[0][9][0] = "Q";
        $array_zweiter_buchstabe[0][9][1] = "K";
        $array_zweiter_buchstabe[1][0][0] = "R";
        $array_zweiter_buchstabe[1][0][1] = "L";
        $array_zweiter_buchstabe[1][1][0] = "S";
        $array_zweiter_buchstabe[1][1][1] = "M";
        $array_zweiter_buchstabe[1][2][0] = "T";
        $array_zweiter_buchstabe[1][2][1] = "N";
        $array_zweiter_buchstabe[1][3][0] = "U";
        $array_zweiter_buchstabe[1][3][1] = "P";
        $array_zweiter_buchstabe[1][4][0] = "V";
        $array_zweiter_buchstabe[1][4][1] = "Q";
        $array_zweiter_buchstabe[1][5][0] = "A";
        $array_zweiter_buchstabe[1][5][1] = "R";
        $array_zweiter_buchstabe[1][6][0] = "B";
        $array_zweiter_buchstabe[1][6][1] = "S";
        $array_zweiter_buchstabe[1][7][0] = "C";
        $array_zweiter_buchstabe[1][7][1] = "R";
        $array_zweiter_buchstabe[1][8][0] = "D";
        $array_zweiter_buchstabe[1][8][1] = "U";
        $array_zweiter_buchstabe[1][9][0] = "E";
        $array_zweiter_buchstabe[1][9][1] = "V";

        $erste_ziffer_northing = substr($this->northing,0,1);
        $zweite_ziffer_northing = substr($this->northing,1,1);

        $zweiter_buchtsabe = $array_zweiter_buchstabe[$erste_ziffer_northing%2][$zweite_ziffer_northing][$this->zone%2];

        $this->zonenfeld = $erster_buchstabe.$zweiter_buchtsabe;

        $this->easting=substr($this->easting,1);
        $this->northing=substr($this->northing,2);


    }

    public function get_zonenfeld(){
        return $this->zonenfeld;
    }
}