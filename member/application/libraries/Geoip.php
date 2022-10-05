<?php
defined('BASEPATH') or exit('No direct script access allowed');

require './vendor/geoip2/autoload.php';

use GeoIp2\Database\Reader;

class Geoip
{
    public function get_city_record($ip)
    {
        try {
            $reader = new Reader('./data/GeoIP2-City-North-America.mmdb');
            $record = $reader->city($ip);
            return $record;
        } catch (Exception $e) {
            return null;
        }
    }
}
