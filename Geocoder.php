<?php
/**
 * Created by JetBrains PhpStorm.
 * User: johannes
 * Date: 20.05.13
 * Time: 16:17
 * To change this template use File | Settings | File Templates.
 */

class Geocoder extends GPS{

    public function get_addresse(){
        $xml = simplexml_load_string(file_get_contents('http://nominatim.openstreetmap.org/reverse?format=xml&lat='.$this->lat_grad.'&lon='.$this->lng_grad.'&zoom=18&addressdetails=1'));
        //print_r($xml);
        return $xml->addressparts->road." ".$xml->addressparts->house_number." ".$xml->addressparts->postcode." ".$xml->addressparts->city." ".$xml->addressparts->county;
    }
}