<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/31
 * Time: 20:17
 */
    $code = $_GET["code"];
    include('getUserLoginInfo.php');
    $userinfo = getUserInfo($code);
    $weixinnumber = $userinfo["openid"];
    if (isset($weixinnumber)) {
        $db_server = 'www.ccjjltx.cn';
        $db_port = '3307';
        $db_user = 'root';
        $db_password = 'usbw';
        $db_name = 'users1';
        $connect = mysqli_connect($db_server, $db_user, $db_password, $db_name, $db_port) or die('Unale to connect');
        $sql = "delete from user where weiXinNumber='" . $weixinnumber . "'";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo "<script>alert('取消绑定成功');</script>";
        } else {
            echo "<script>alert('取消绑定失败');</script>";
        }
    } else {
        echo "<script>alert('没有获取到用户微信号');</script>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width-device-width,initial-scale=1" />
    <title></title>
    <script type="text/javascript" src="http://1.479258585.applinzi.com/OAuth2.0/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="http://1.479258585.applinzi.com/OAuth2.0/js/jquery.mobile-1.4.5.js"></script>
    <link rel="stylesheet" type="text/css" media="screen,print" href="http://1.479258585.applinzi.com/OAuth2.0/css/jquery.mobile-1.4.5.css" />

</head>
<script type="text/javascript">
    function back(){
        WeixinJSBridge.call('closeWindow');
    }
</script>

<body>
<header data-role="header">
    <a href="" onclick="back()"  data-role="button" data-icon="back">后退</a>
    <h1>取消绑定</h1>
    <a href="" data-role="button" data-icon="forward">前进</a>
</header>

</body>
</html>