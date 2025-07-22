<?php
class Ip_address
{
    static private $ip;
    static private $port;

    static private $address;

    static public function setAddress(string $ip, int $port){
        self::$ip = $ip;
        self::$port = $port;
        self::$address = "$ip:$port";
    }

    static public function getAddress(){
        return self::$address;
    }
}

?>