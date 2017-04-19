<?php
namespace app\weichat\controller;
use think\Log;
use think\Request;
class PubUser
{
    public $userInfo;
    public $wxId;
    
    public function index()
    {
        return 'pub_user';
    }
    function get_access_token(){
        $appid="wxac883d4c9f20abd0";
        $secret="069800b6691ac71d76fde3bb68d00c86";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        return curlGet($url);
    }
    function init_user(){
        return 'ok';
    }
    public function __construct(){
            parent::__construct();

            //只要用户一访问此模块，就登录授权，获取用户信息
            $this->userInfo = $this->getWxUserInfo();
        }
    

        /**
         * 确保当前用户是在微信中打开，并且获取用户信息
         *
         * @param string $url 获取到微信授权临时票据（code）回调页面的URL
         */
    private function getWxUserInfo($url = '') {
        //微信标记（自己创建的）
        $wxSign = $this->input->cookie('wxSign');
        //先看看本地cookie里是否存在微信唯一标记，
        //假如存在，可以通过$wxSign到redis里取出微信个人信息（因为在第一次取到微信个人信息，我会将其保存一份到redis服务器里缓存着）
        if (!empty($wxSign)) {
            //如果存在，则从Redis里取出缓存了的数据
            $userInfo = $this->model->redisCache->getData("weixin:sign_{$wxSign}");
            if (!empty($userInfo)) {
                //获取用户的openid
                $this->wxId = $userInfo['openid'];
                //将其存在cookie里
                $this->input->set_cookie('wxId', $this->wxId, 60*60*24*7);
                return $userInfo;
            }
        }

        //获取授权临时票据（code）
        /*$code = $_GET['code'];
        if (empty($code)) {
            if (empty($url)) {
                $url = rtirm($_SERVER['QUERY_STRING'], '/');
                //到WxModel.php里获取到微信授权请求URL,然后redirect请求url
                redirect($this->model->wx->getOAuthUrl(baseUrl($url)));
            }
        }*/


    }
    function callback_user(){
        Log::init([
            'type'  =>  'File',
            'path'  =>  APP_PATH.'logs/'
        ]);
        $request = Request::instance();
        $param = $request->param();
        Log::write($param);
    }
    function get_user(){
        $ACCESS_TOKEN=$this->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token=$ACCESS_TOKEN";
    }

    
}

