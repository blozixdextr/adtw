<?php

namespace App\Apis;

use Config;

class Twitch
{
    protected $timeout = 10;
    protected $clientId = '';
    protected $clientSecret = '';
    protected $accessToken = '';

    public function __construct() {
        $this->clientId = Config::get('twitch.client_id');
        $this->clientSecret = Config::get('twitch.client_secret');
    }

    public function getAccessToken() {

    }

    public function get_url_contents($url) {
        $crl = curl_init();
        curl_setopt ($crl, CURLOPT_URL, $url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $ret = curl_exec($crl);
        curl_close($crl);

        return $ret;
    }

    public function post_url_contents($url, $fields) {
        $fields_string = '';
        foreach($fields as $key=>$value) {
            $fields_string .= $key.'='.urlencode($value).'&';
        }
        rtrim($fields_string, '&');
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL,$url);
        curl_setopt($crl, CURLOPT_POST, count($fields));
        curl_setopt($crl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $ret = curl_exec($crl);
        curl_close($crl);

        return $ret;
    }
}