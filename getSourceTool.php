<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/29
 * Time: 14:28
 */
    //获取课程表的方法
    function getCourse($user, $pass)
    {
        $url = "http://www.ccjjltx.cn/ccj/scheduleccj2.php";
        $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        $datas=json_decode($data,true);
        $weekarray=array("日","一","二","三","四","五","六");
      $weekarrays=array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
         $today="星期".$weekarray[date("w")];
         $todays=$weekarrays[date("w")];
        $courseArray=array();
        $courseArray[]=array("Title"=>$today."的课程表","Description"=>"","PicUrl"=>"","Url"=>"");
        if($todays!="Sunday"&&$todays!="Saturday") {
            for ($i = 0; $i < count($datas); $i++) {
                $content = trimall($datas[$i][$todays]);
                if (strlen(trim($datas[$i][$todays])) != 2) {
                    $courseArray[] = array(
                        "Title" => "" . $datas[$i]['time'] . "" . $content . "",
                        "Description" => "",
                        "PicUrl" => "",
                        "Url" => ""
                    );
                }
            }
            $courseArray[] = array(
                "Title" => "详情",
                "Description" => "",
                "PicUrl" => "",
                "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx86b797cf63c262b3&redirect_uri=http://479258585.applinzi.com/baiduMapSearch/courseTable.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
            );
        }else{
            return "今天不用上课";
        }
        return $courseArray;
    }

//整个课程表
function getAllCourse($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/scheduleccj2.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $datas=json_decode($data,true);
    return    $datas;
}
function trimall($str)
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str);
}
    //获取考试成绩
    function getPersonMessage($user,$pass){
        $url = "http://www.ccjjltx.cn/ccj/personMessageccj.php";
        $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        $datas=json_decode($data,true);
        $personMessage=array();
        $semester[] = array();
        $j = 0;
        for ($i = 0; $i < count($datas); $i++) {
            if (!empty($datas[$i]['semester'])) {
                $semester[$j] = $datas[$i]['semester'];
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
        $personMessage[]=array("Title"=>"".$semesterCount[count($semesterCount)-2]."的成绩","Description"=>"","PicUrl"=>"","Url"=>"");
        $j=0;
        for($i=0;$i<count($datas);$i++) {
            if ($datas[$i]['semester']==($semesterCount[count($semesterCount)-2])&&$j<8) {
                $personMessage[] = array(
                    "Title" => "课程名称: " . $datas[$i]['courseName'] . "\n学分: " . $datas[$i]['courseCredit'] . "\n考试类型: " . $datas[$i]['evaluationMode'] . "\n分数: "
                        . $datas[$i]['grade'],
                    "Description" => "",
                    "PicUrl" => "",
                    "Url" => ""
                );
                $j++;
            }
        }  $personMessage[] = array(
            "Title" => "还想进一步了解我请戳我" ,
            "Description" => "",
            "PicUrl" => "",
            "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx86b797cf63c262b3
&redirect_uri=http://479258585.applinzi.com/baiduMapSearch/getAllScore.php&response_type
=code&scope=snsapi_userinfo&state=1#wechat_redirect"
        );
        return $personMessage;//输出结果
    }
//获取学生的考试成绩
function getScore($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/personMessageccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $datas=json_decode($data,true);
    return $datas;//输出结果
}
   //获取学生的期末考试表
  function getExamTime($user,$pass){
      $url = "http://www.ccjjltx.cn/ccj/examTimeccj.php";
      $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
      $ch = curl_init();//初始化curl
      curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
      curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
      curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
      curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
      $data = curl_exec($ch);//运行curl
      curl_close($ch);
      $datas=json_decode($data,true);
      $examTimeArray=array();
      $examTimeArray[]=array("Title"=>"考试时间表","Description"=>"","PicUrl"=>"","Url"=>"");
      for($i=0;$i<count($datas);$i++){
              $examTimeArray[] = array(
                  "Title" => "课程名称: " . $datas[$i]['courseName']."\n考试日期: ".$datas[$i]['examDate'].""."\n考试时间: ".$datas[$i]['examTime']."\n考场名称: "
              .$datas[$i]['examLocation']."\n考试座位: ".$datas[$i]['examSeat'],
                  "Description" => "",
                  "PicUrl" => "",
                  "Url" => ""
              );
          }
      return $examTimeArray;
  }
//获取学生的奖惩情况
function getAwardPunishment($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/awardPunishmentccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
   $datas=json_decode($data,true);
    $awardPunishment=array();
    $awardPunishment[]=array("Title"=>"奖惩记录","Description"=>"","PicUrl"=>"","Url"=>"");
   for($i=0;$i<count($datas);$i++){
       $awardPunishment[] = array(
            "Title" => "学年：" .$datas[$i]['academicYear']."\n学期: ".$datas[$i]['semester']."\n奖励级别: ".$datas[$i]['rewardLevel']."\n奖励原因："
               .$datas[$i]['rewardFrom']."\n奖励单位/部门：".$datas[$i]['rewardUnits']."\n奖励日期：".$datas[$i]['rewardData'],
          "Description" => "",
            "PicUrl" => "",
           "Url" => ""
        );
    }
    return $awardPunishment;
}
//获取学生的考勤情况表
function getAttendanceMessage($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/attendanceMessageccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $datas=json_decode($data,true);
    $attendanceMessage=array();
    $attendanceMessage[]=array("Title"=>"考勤信息","Description"=>"","PicUrl"=>"","Url"=>"");
    for($i=0;$i<count($datas);$i++){
        $attendanceMessage[] = array(
            "Title" => "课程编号：" .$datas[$i]['courseCode']."\n课程名称: ".$datas[$i]['courseName']."\n详细信息: ".$datas[$i]['detailMessage'],
            "Description" => "",
            "PicUrl" => "",
            "Url" => ""
        );
    }
    return $attendanceMessage;
}
//违规记录表
function getViolationsRecord($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/violationsRecordccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $datas=json_decode($data,true);
    $violationsRecord=array();
    $violationsRecord[]=array("Title"=>"违规记录","Description"=>"","PicUrl"=>"","Url"=>"");
    for($i=0;$i<count($datas)-1;$i++){
        $violationsRecord[] = array(
            "Title" => "晚归情况：" .$datas[$i]['recode']."\n用电情况: ".$datas[$i+1]['recode'],
            "Description" => "",
            "PicUrl" => "",
            "Url" => ""
        );
    }
    return $violationsRecord;
}
//模拟登陆登录验证
function mockLogin($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/enterccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    $data = (array)json_decode($data);
   return $data['choose'];
}
   //获取个人信息
function getPersonInfoMain($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/personMessageccj2.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    $datas = (array)json_decode($data);
    $personInfo="";
    for($i=0;$i<count($datas)-1;$i++){
            $personInfo.=$datas[$i]->keys.": ".$datas[$i]->values."\n";
    }
    return $personInfo;
}
//获取开设课程
function getOpenCourses($user,$pass){
    $url = "http://www.ccjjltx.cn/ccj/openCoursesccj.php";
    $curlPost = 'user=' . urlencode($user) . '&pass=' . urlencode($pass);
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    $datas=json_decode($data);
    return $datas;
//    $openCourses=array();
//    $openCourses[]=array("Title"=>"学院开设的总课程","Description"=>"","PicUrl"=>"","Url"=>"");
//    for($i=0;$i<count($datas);$i++){
//        $openCourses[] = array(
//            "Title" => "课程代码：" .$datas[$i]->courseCode1."\n课程名称: ".$datas[$i]->courseName1."\n学分: ".$datas[$i]->credit1
//                ."\n考核方式: ".$datas[$i]->examMethod1,
//            "Description" => "",
//            "PicUrl" => "",
//            "Url" => ""
//        );
//    }
//    return $openCourses;
}
print_r(mockLogin("1440112131","14440923106363"));
?>