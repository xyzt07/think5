<?php
namespace app\weichat\model;
use think\Model;
class Weixin extends Model{
    private $appid="wxac883d4c9f20abd0";
    private $secret="069800b6691ac71d76fde3bb68d00c86";
    function get_msg($data){
        //事件推送
        if($data['MsgType']=='event'){
            if($data['event']=='subscribe'){
                $Fllow= model("Follow");
                $is_exit=$Follow->where('openid', $data['FromUserName'])->find();
                if(count($is_exit)>=1){
                    //model("User")->update_user_info($data['FromUserName']);
                }
                else{
                    model("User")->init_user($data['FromUserName']);
                    //$Fllow->data(['openid'=>$data['FromUserName']]);
                    $Follow->save();
                    $User= model("User");
                    $User->add_user_info($Follow->id);
                }
               
            }
            
        }
        //消息推送
        elseif($data['MsgType']=='text'){
            # code...
        }else{

        }
    }
    function get_access_token(){
        $appid=$this->appid;
        $secret=$this->secret;
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        $response=curlGet($url);
        $responseObj = str_replace('"','',explode(':',explode(',',$response)[0])[1]);
        return $responseObj ;
    }
}