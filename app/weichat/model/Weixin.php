<?php
namespace app\weichat\model;
use think\Model;
class Weixin extends Model{
    function get_msg($data){
        //事件推送
        if($data['MsgType']=='event'){
            if($data['event']=='subscribe'){
                $Fllow= new Follow;
                $Fllow->data(['openid'=>$data['FromUserName']]);
                $Follow->save();
                $User= new User;
                $User->add_user_info($Follow->id);
                }
            }

        }
        //消息推送
        elseif ($data['MsgType']=='text') {
            # code...
        }else{

        }
    }
}