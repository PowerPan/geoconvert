<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 19.05.13
 * Time: 13:49
 * To change this template use File | Settings | File Templates.
 */
include_once("geoconvert.php");

switch($_GET['func']){
    case "set_dezi":    $geo = new geoconvert();
                        $geo->set_gps_dezi($_POST['lat'],$_POST['lng']);
                        $json = '{ "dezi": "'.$geo->get_gps_dezi().'"
                                   ,"bogen":  "'.$geo->get_gps_bogen().'"
                                   ,"bogen_sek":  "'.$geo->get_gps_bogen_sek().'"
                                   ,"utm":  "'.$geo->get_utm().'"
                                   }';

                        echo $json;
                        break;
}