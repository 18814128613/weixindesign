<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <style type="text/css">
        table {
            border-collapse: collapse;
            text-align: center;
            vertical-align: middle;
        }

        /*table tr{ height:25px;}*/
        table tr {
            height: auto;
        }

        table td {
            width: 150px;
        }

        /*.bg1{ background:#ccc;}*/
        /*.bg1{ background:#5CACEE;}*/
        table tr:hover {
            background: #87CEEB;
        }

        /*table tr:hover{ background:#cc0000;}*/
        /*.bg2{ background:#999;}*/
        /*.bg2{ background:#4876FF;}*/
        .t1, .t2, .t3, .t4, .b1, .b2, .b3, .b4, .tab {
            display: block;
            overflow: hidden;
        }

        .t1, .t2, .t3, .b1, .b2, .b3 {
            height: 1px;
        }

        .t2, .t3, .t4, .b2, .b3, .b4 {
            border-left: 2px solid #f6f;
            border-right: 2px solid #f6f;
        }

        .t1, .b1 {
            margin: 0 5px;
            background: #F6F;
        }

        .t2, .b2 {
            margin: 0 3px;
            border-width: 2px;
        }

        .t3, .b3 {
            margin: 0 2px;
        }

        .t4, .b4 {
            height: 2px;
            margin: 0 1px;
        }

        /*.tab{ height:130px; background:#F7F8F9;}*/

        /*.tab_width{ width:700px; margin:0 auto; cursor:pointer;}*/
        .tab_width {
            width: 610px;
            margin: 0 auto;
            cursor: pointer;
        }

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
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 17:32
 */
include("getSourceTool.php");
include("getUserLoginInfo.php");
$code = $_GET["code"];
$userinfo = getUserInfo($code);
$weixinnumber = $userinfo["openid"];
$userInfo=getUserLoginInfo($weixinnumber);
$array=getScore($userInfo['username'],$userInfo['password']);
$semester[] = array();
$j = 0;
for ($i = 0; $i < count($array); $i++) {
    if (!empty($array[$i]['semester'])) {
        $semester[$j] = $array[$i]['semester'];
        $j++;
    }
}
sort($semester);
$semester1 = $semester[0];
$semesterCount[] = array();
$semesterCount[0] = $semester1;
$r = 1;
for ($j = 1; $j < count($semester); $j++) {
    if ($semester1 != $semester[$j]) {
        $semesterCount[$r] = $semester[$j];
        $semester1 = $semester[$j];
        $r++;
    }
}
$j = 0;
while (isset($semesterCount[$j])) {
    ?>

    <div class="tab_width">
        <!--    <b class="t1"></b><b class="t2"></b><b class="t3"></b><b class="t4"></b>-->
        <div class="tab">
            <table>

                <tr>
                    <td style="font-family: 微软雅黑"><?php echo $semesterCount[$j]; ?></td>
                </tr>

                <tr>
                    <td style="font-family: 新宋体">课程名称</td>
                    <td style="font-family: 新宋体">学分</td>
                    <td style="font-family: 新宋体">考试类型</td>
                    <td style="font-family: 新宋体">分数</td>

                </tr>

                <?php
                for ($i = 0; $i < count($array); $i++) {
                    if ($array[$i]['semester'] == $semesterCount[$j]) {
                        ?>


                        <tr class="bg1">
                            <td style="font-family: 幼圆"><?php echo $array[$i]['courseName'] ?></td>
                            <td style="font-family: 幼圆"><?php echo $array[$i]['courseCredit'] ?></td>
                            <td style="font-family: 幼圆"><?php echo $array[$i]['evaluationMode'] ?></td>
                            <td style="font-family: 幼圆"><?php echo $array[$i]['grade'] ?></td>

                        </tr>

                        <!--                        <tr class="bg2">-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['time'] ?><!--</td>-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['Monday'] ?><!--</td>-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['Tuesday'] ?><!--</td>-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['Wednesday'] ?><!--</td>-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['Thursday'] ?><!--</td>-->
                        <!--                            <td style="font-family: 幼圆">--><?php //echo $array[$i]['Friday'] ?><!--</td>-->
                        <!--                        </tr>-->

                    <?php

                    }
                }
                $j++;
                ?>

            </table>
        </div>
        <!--    <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>-->
    </div>

<?php
}
?>

</body>

</html>




<?php
//
//
//while (isset($semesterCount[$j])) {
//    echo "<table><tr><td >";
//    echo $semesterCount[$j];
//    echo "</td></tr>";
//    echo "<tr><td>课程名称</td><td>学分</td><td>考试类型</td><td>分数</td></tr>";
//    for ($i = 0; $i < count($array); $i++) {
//        if ($array[$i]['semester'] == $semesterCount[$j]) {
//            ?>
<!--            <tr>-->
<!--                <td>--><?php //echo $array[$i]['courseName']; ?><!--</td>-->
<!--                <td>--><?php //echo $array[$i]['courseCredit']; ?><!--</td>-->
<!--                <td>--><?php //echo $array[$i]['evaluationMode']; ?><!--</td>-->
<!--                <td>--><?php //echo $array[$i]['grade']; ?><!--</td>-->
<!--            </tr>-->
<!--        --><?php //}
//    }
//    echo "</table>";
//    $j++;
//} ?>
