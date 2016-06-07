<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/30
 * Time: 23:20
 */
if(isset($_POST['weixinnumber'])) {
    $weixinnumber = $_POST['weixinnumber'];
    $username = $_POST['user'];
    $password = $_POST['pass'];
    include("getSourceTool.php");
    if (mockLogin($username, $password)) {
        $db_server = 'www.ccjjltx.cn';
        $db_port = '3307';
        $db_user = 'root';
        $db_password = 'usbw';
        $db_name = 'users1';
        if (isset($username) && isset($password) && isset($weixinnumber)) {
            $connect = mysqli_connect($db_server, $db_user, $db_password, $db_name, $db_port) or die('Unale to connect');
            $sql = "insert  into user(weiXinNumber,username,password)values('$weixinnumber','$username','$password')";
            $result = mysqli_query($connect, $sql);
            if ($result) {
                echo "<script>alert('绑定Mysise系统成功!');</script>";
            } else {
                echo "<script>alert('请输入正确的用户名和密码');</script>";
            }
        } else {
            echo "<script>alert('请输入用户名或密码不能为空');</script>";
        }
    } else {
        echo "<script>alert('请输入正确的学号和密码');</script>";
    }
}else {
    echo "<script>alert('非法进入登录页面');</script>";
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
    <h1>绑定mysise系统</h1>
    <a href="" data-role="button" data-icon="forward">前进</a>
</header>
</body>
</html>
