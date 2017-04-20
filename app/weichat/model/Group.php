<?php
namespace app\weichat\model;
use think\Model;
class Group extends Model{
    function get_group_for_uid($uid){

    }
    function get_group_info($groupid){

    }
    function get_list_group(){
        $access_token=model("Weixin")->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$access_token";
        $response=curlGet($url);
        $resarr=json_decode($response,true);
        for($i=0;$i<count($resarr['tags']);$i++){
            $this->update_group($resarr['tags'][$i]);
        }
        //$this->update_group($resarr['tags']);
        return $resarr['tags'];
    }
    function create_group($name){
        $access_token=model("Weixin")->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$access_token";
        $json_data=json_encode(array('tag'=>array("name"=>$name)));
        $response=curl_post_ssl($url,$json_data);
        $resarr=json_decode($response,true);
        try{
            $this->update_group($resarr['tag']);
        }
        catch(\Exception $e){
            return $resarr;
        }
        //$this->update_group($resarr['tag']);
        return $resarr;
    }
    function update_group($info){
        //if(count($info)>1)
        $exit=model("Group")->where(array("id"=>$info['id']))->count();
        if($exit>=1){
            $id=$info['id'];
            $info['name']=unicodeDecode($info['name']);
            unset($info['id']);
            //$data=array($info,array("id"=>$id));
            //dump($data);
            model("Group")->save($info,["id"=>$id]);
        }else{
            $info['name']=unicodeDecode($info['name']);
            model("Group")->save($info);
        }
        
    }
    function del_group($id){
        $access_token=model("Weixin")->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=$access_token";
        $json_data=json_encode(array('tag'=>array("id"=>$id)));
        $response=curl_post_ssl($url,$json_data);
        $resarr=json_decode($response,true);
        if($resarr['errcode']==0){
            return true;
        }
        else{
            return $resarr;
        }
    }
    function get_user_group($openid){
        $access_token=model("Weixin")->get_access_token();
        $url="https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=$access_token";
        $json_data=json_encode(array("openid"=>$openid)));
        $response=curl_post_ssl($url,$json_data);
        $resarr=json_decode($response,true);
        //$group_list=$resarr['tagid_list'];
        return $resarr;
    }
    function set_user_group($uid,$lis){
        $group_access=model('GroupAccess');
        for($i=0;$i<count($lis);$i++){
            unset($data);
            $data['uid']=$uid;
            $data['groupid']=$lis[$i];
            $num=$group_access->where($data)->count();
            if(!$num){
                $group_access->save($data);
            }
        }
    }
    function sync_user_group($openid){
        $lis=$this->get_user_group();
        $uid=model("User")->openid_get_uid($openid);
        $this->set_user_group($uid,$lis);
    }
}