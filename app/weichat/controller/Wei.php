<?php
namespace app\weichat\controller;

class Wei
{
    public function index()
    {
        return 'weichat';
    }
    function get_access_token(){
        $appid="wxfff6d196725f7d46";
        $secret="f4e856ec75d1b5801ac4ed5bfe1d10eb";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        return curlGet($url);
    }
}
