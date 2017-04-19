<?php
namespace app\weichat\model;

class PubUser {
        public $appId;
        public $appSecret;
        public $token;

        public function __construct() {
            parent::__construct();

            //审核通过的移动应用所给的AppID和AppSecret
            $this->appId = 'wx0000000000000000';
            $this->appSecret = '00000000000000000000000000000';
            $this->token = '00000000';
        }

        /**
         * 获取微信授权url
         * @param string 授权后跳转的URL
         * @param bool 是否只获取openid，true时，不会弹出授权页面，但只能获取用户的openid，而false时，弹出授权页面，可以通过openid获取用户信息
         *   
        */
       public function getOAuthUrl($redirectUrl, $openIdOnly, $state = '') {
        $redirectUrl = urlencode($redirectUrl);
        $scope = $openIdOnly ? 'snsapi_base' : 'snsapi_userinfo';
        $oAuthUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appId}&redirect_uri={$redirectUrl}&response_type=code&scope=$scope&state=$state";
        return $oAuthUrl;
       }

