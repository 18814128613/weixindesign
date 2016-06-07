<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>

    <link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <style type="text/css">
        table { border-collapse:collapse;text-align:center; vertical-align:middle;}
        /*table tr{ height:25px;}*/
        table tr{ height:auto;}
        table td{ width:150px;}
        /*.bg1{ background:#ccc;}*/
        /*.bg1{ background:#5CACEE;}*/
        table tr:hover{ background:#87CEEB;}
        /*table tr:hover{ background:#cc0000;}*/
        /*.bg2{ background:#999;}*/
        /*.bg2{ background:#4876FF;}*/
        .t1,.t2,.t3,.t4,.b1,.b2,.b3,.b4,.tab{ display:block; overflow:hidden;}
        .t1,.t2,.t3,.b1,.b2,.b3{ height:1px;}
        .t2,.t3,.t4,.b2,.b3,.b4{ border-left:2px solid #f6f; border-right:2px solid #f6f;}
        .t1,.b1{ margin:0 5px; background: #F6F;}
        .t2,.b2{ margin:0 3px; border-width:2px;}
        .t3,.b3{ margin:0 2px;}
        .t4,.b4{ height:2px; margin:0 1px;}
        /*.tab{ height:130px; background:#F7F8F9;}*/

        /*.tab_width{ width:700px; margin:0 auto; cursor:pointer;}*/
        .tab_width{ width:610px; margin:0 auto; cursor:pointer;}
        td {
            border: 1px solid #cccccc;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            color: darkblue;
        }
    </style>

</head>
<body>

<?php
include("getSourceTool.php");
include("getUserLoginInfo.php");
$code = $_GET["code"];
$userinfo = getUserInfo($code);
$weixinnumber = $userinfo["openid"];
$userInfo=getUserLoginInfo($weixinnumber);
$array=getAllCourse($userInfo['username'],$userInfo['password']);
?>

<div class="tab_width">
    <!--    <b class="t1"></b><b class="t2"></b><b class="t3"></b><b class="t4"></b>-->
    <div class="tab">
        <table>
            <tr>
                <td style="font-family: 微软雅黑">上课时间</td>
                <td style="font-family: 微软雅黑">周一</td>
                <td style="font-family: 微软雅黑">周二</td>
                <td style="font-family: 微软雅黑">周三</td>
                <td style="font-family: 微软雅黑">周四</td>
                <td style="font-family: 微软雅黑">周五</td>

            </tr>

            <?php
            for($i=0;$i<count($array);$i++) {
                if($i % 2 == 0){
                    ?>

                    <tr class="bg1">
                        <td style="font-family: 幼圆"><?php echo $array[$i]['time'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Monday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Tuesday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Wednesday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Thursday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Friday'] ?></td>
                    </tr>

                <?php

                }else{
                    ?>

                    <tr class="bg2">
                        <td style="font-family: 幼圆"><?php echo $array[$i]['time'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Monday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Tuesday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Wednesday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Thursday'] ?></td>
                        <td style="font-family: 幼圆"><?php echo $array[$i]['Friday'] ?></td>
                    </tr>

                <?php

                }
                ?>


            <?php
            }
            ?>


        </table>
    </div>
    <!--    <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>-->
</div>


</body>


</html>
