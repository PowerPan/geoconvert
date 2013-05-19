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
    case "test":    $geo = new geoconvert();
                    $geo->set_gps_dezi($_POST['lat'],$_POST['lng']);
                    print_r($geo->get_gps_dezi());
                    print_r($geo->get_gps_bogen());
                    print_r($geo->get_gps_bogen_sek());
                    print_r($geo->get_utm());
                    break;
}