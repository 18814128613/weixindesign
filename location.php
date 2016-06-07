<?php
//缓存中保存用户地理位置
function setLocation($openid,$locationX,$locationY){
    $mmc=memcache_init();
    if($mmc==true){
        $location=array("locationX"=>$locationX,"locationY"=>$locationY);
        memcache_set($mmc,$openid,json_encode($location),60);

        return  "您的位置已缓存. \n仙子阿可发送'附近'加目标的命令，如“附近酒店”";
    }else{
        return  "未启用缓存，请先开启服务器的缓存功能.";
    }
}

//从缓存中读取用户地理位置信息
function getLocation($openid){
    $mmc=memcache_init();
    if($mmc==true){
        $location=memcache_get($mmc,$openid);
        if(!empty($location)){
            return json_decode($location,true);
        }else{
            return  "未启用缓存，请先开启服务器的缓存功能";
        }
    }
}
?>