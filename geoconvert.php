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

    // N/S = Y-Achse
    // O/W = X-Achse

    private function gps_dezi2bogen(){
        //Umrechnung von Dezimal dd.ddddd nach dd mm.mmm
        //X-Achse
        $nachkomma = explode(".",$this->gps_dezi['x']);
        $mm = $nachkomma[1]*60;

        $this->gps_bogen['x']['grad'] = $nachkomma[0];
        $this->gps_bogen['x']['minute'] = $mm;

        //Y-Achse
        $nachkomma = explode(".",$this->gps_dezi['y']);
        $mm = $nachkomma[1]*60;
        $this->gps_bogen['y']['grad'] = $nachkomma[0];
        $this->gps_bogen['y']['minute'] = $mm;
    }

    private function gps_bogen2bogen_sek(){
        //Umrechnung von dd mm.mmm nach dd mm ss.s
        //X-Achse
        $nachkomma = explode(".",$this->gps_bogen['x']['minute']);
        $ss = $nachkomma[1]*60;

        $this->gps_bogen_sek['x']['grad'] = $this->gps_bogen['x']['grad'];
        $this->gps_bogen_sek['x']['minute'] = $nachkomma[0];
        $this->gps_bogen_sek['x']['sekunde'] = $ss;

        //Y-Achse
        $nachkomma = explode(".",$this->gps_bogen['y']['minute']);
        $ss = $nachkomma[1]*60;

        $this->gps_bogen_sek['y']['grad'] = $this->gps_bogen['y']['grad'];
        $this->gps_bogen_sek['y']['minute'] = $nachkomma[0];
        $this->gps_bogen_sek['y']['sekunde'] = $ss;
    }

    private function gps_bogen2dezi(){
        //Umrechnung von Dezimal dd mm.mmm nach dd.ddddd
        //X-Achse
        $this->gps_dezi['x'] = $this->gps_bogen['x']['grad'] + ($this->gps_bogen['x']['bogen']/60);

        //Y-Achse
        $this->gps_dezi['y'] = $this->gps_bogen['y']['grad'] + ($this->gps_bogen['y']['bogen']/60);
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

    public 

}