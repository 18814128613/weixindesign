<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/31
 * Time: 19:23
 */
//通过$weixinnumber获取用户信息
function getUserLoginInfo($weixinnumber)
{
    $db_server = 'www.ccjjltx.cn';
    $db_port = '3307';
    $db_user = 'root';
    $db_password = 'usbw';
    $db_name = 'users1';
    $connect = mysqli_connect($db_server, $db_user, $db_password, $db_name, $db_port) or die('Unale to connect');
    $sql = "select username,password from user where weiXinNumber='".$weixinnumber."'";
    $result =mysqli_query($connect,$sql);
    if($result){
    while($row = mysqli_fetch_assoc($result)){
        return $row;
    }
    }else{
        echo "<script>alert('查询出错！');</script>";
    }
}


//通过$code获取用户信息
function getUserInfo($code)
{
    $appid = "wx86b797cf63c262b3";
    $appsecret = "b102b27a5564a19ae268684f19566206";
    $access_token = "";

    $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $access_token_json = https_request($access_token_url);
    $access_token_array = json_decode($access_token_json, true);
    $access_token = $access_token_array['access_token'];
    $openid = $access_token_array['openid'];

    $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
    $userinfo_json = https_request($userinfo_url);
    $userinfo_array = json_decode($userinfo_json, true);
    return $userinfo_array;
}

function https_request($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'ERROR' . curl_error($curl);
    }
    curl_close($curl);
    return $data;
}
?>