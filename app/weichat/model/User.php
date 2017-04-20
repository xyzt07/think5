<?php
namespace app\weichat\model;
use think\Model;
class User extends Model{
    function get_user_info($openid){
        $weixin=model('Weixin');
        $access_token=$weixin->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $response=curlGet($url);
        $resarr=json_decode($response,true);
        //model('User')->add_user_info($resarr);
        return $resarr;
        //dump($resarr);
    }
    function init_user($openid){
        $info=$this->get_user_info($openid);
        $uid=$this->add_user_info($info);
        $data['uid']=$uid;
        $data['openid']=$info['openid'];
        model("Follow")->save($data);

    }
    function add_user_info($save){
        model('User')->add($save);
        return model('User')->uid;
    }
    function sync_user(){

    }
    function update_user_info(){
        
    }
    function get_user_group(){

    }
    function openid_get_uid($openid){
        $user=model("Follow")->where(['openid'=>$openid])->find();
        return $user['uid'];
    }
}