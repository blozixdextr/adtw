<?php

namespace App\Apis;

use Config;
use Session;
use Redirect;
use Auth;

class Twitch
{
    protected $timeout = 10;
    protected $clientId = '';
    protected $clientSecret = '';
    protected $accessToken = '';
    protected $baseUrl = 'https://api.twitch.tv/kraken';
    protected $redirectUrl = '/auth/twitch/callback';
    protected $loginUrl = '/auth/twitch';

    public function __construct() {
        $this->clientId = Config::get('twitch.client_id');
        $this->clientSecret = Config::get('twitch.client_secret');
        $this->redirectUrl = url($this->redirectUrl);
        $this->loginUrl = url($this->loginUrl);
    }

    public function getLoginUrl() {
        $state = uniqid();
        Session::put('twitch.state', $state);
        $url = $this->baseUrl.'/oauth2/authorize?response_type=code&client_id='.$this->clientId.'&redirect_uri='.$this->redirectUrl.'&scope=user_read+channel_read&state='.$state;

        return $url;
    }

    public function setAccessToken($accessToken) {
        Session::put('twitch.accessToken', $accessToken);
        Session::save();
    }

    public function getAccessToken($redirectOnFail = false) {
        $accessToken = Session::get('twitch.accessToken', false);
        if (!$accessToken && $redirectOnFail) {
            Redirect::to($this->loginUrl);
        }

        return $accessToken;
    }

    public function getIdentity() {
        $result = $this->get_url_contents('/user', [], true);

        return $result;
    }

    public function getChannel() {
        $result = $this->get_url_contents('/channel', [], true);

        return $result;
    }

    public function getVideos($channel, $limit = 10) {
        $result = $this->get_url_contents('/channels/'.$channel.'/videos', ['limit' => $limit], true);

        return $result;
    }

    public function getStream($user) {
        $chanelName = $user->twitch_channel->name;
        $result = $this->get_url_contents('/streams/'.$chanelName, [], true);

        return $result;
    }

    public function checkAuth($code, $state) {
        if ($state != Session::get('twitch.state')) {
            return false;
        }
        $result = $this->post_url_contents('/oauth2/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUrl,
            'code' => $code,
            'state' => $state
        ]);

        if ($result && $result->access_token) {
            $this->setAccessToken($result->access_token);
            return true;
        } else {
            return false;
        }

    }

    public function get_url_contents($url, $fields, $oauth = false) {
        if ($oauth) {
            $fields['oauth_token'] = $this->getAccessToken();
        }
        $fields_string = '';
        foreach($fields as $key => $value) {
            $fields_string .= $key.'='.urlencode($value).'&';
        }
        rtrim($fields_string, '&');

        $url = $this->baseUrl.$url.'?'.$fields_string;

        $crl = curl_init();
        curl_setopt ($crl, CURLOPT_URL, $url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $ret = curl_exec($crl);
        curl_close($crl);

        $ret = json_decode($ret);

        return $ret;
    }

    public function post_url_contents($url, $fields, $oauth = false) {

        $url = $this->baseUrl.$url;

        if ($oauth) {
            $fields['oauth_token'] = $this->getAccessToken();
        }

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

        $ret = json_decode($ret);

        return $ret;
    }
}