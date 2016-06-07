<?php
/*
 * 微信公众平台使用百度地图接口实现附近搜索功能
 * 实现思路:
 * 1.首先实现在缓存中保存用户地理位置功能
 * 2.其次实现从缓存中读取用户地理位置信息
 * 3.然后通过百度地图接口实现搜索附近信息的功能
 * 4.最后实现公众号接收地理位置消息处理功能
 */
define("TOKEN","jiaweiweixin");
$wechatObj = new wechatCallbackapiTest();
if(!isset($_GET['echostr'])){
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class  wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $this->logger("R" . $postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE) {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
            }
            $this->logger("T".$result);
            echo $result;
        } else {
            echo "";
            exit;
        }
    }

    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event) {
            case "subscribe":

                $content = "欢迎帅帅的你，关注炜哥源代码请绑定mysise系统，然后发送关键字就能获取到相应的信息" . '例如 "课程表"' . "";
               // $content=$this->catchEntitiesFromLocation("银行","23.450306","113.494843","5000");

                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
        }
        //$result = $this->transmitNews($object, $content);
       $result = $this->transmitText($object, $content);
        return $result;
    }

    //图文消息回复
    private function transmitNews($obj,$arr_item){
        if(!is_array($arr_item)){
            return ;
        }
        $itemTpl="
        <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
</item>
        ";
        $item_str="";
        foreach($arr_item as $item){
            $item_str.=sprintf($itemTpl,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
        }
        $newsTpl="
        <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[]]></Content>
            <ArticleCount>%s</ArticleCount>
            <Articles>
            $item_str
</Articles>
</xml>
        ";
        $result=sprintf($newsTpl,$obj->FromUserName,$obj->ToUserName,time(),count($arr_item));
        return $result;
    }

    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        $category = substr($keyword, 0, 6);
        $entity = trim(substr($keyword, 6, strlen($keyword)));
        //用户名
        include("getUserLoginInfo.php");
        $res=getUserLoginInfo($object->FromUserName);
        if(isset($res)) {
            $user = $res['username'];
            $pass = $res['password'];
        //获取无课表
        include("getSourceTool.php");
        switch ($keyword) {
            case "附近":
                include("location.php");
                $location =getLocation($object->FromUserName);
                if (is_array($location)) {
                    include("mapbaidu.php");
                //$content=$entity;
                    $content = catchEntitiesFromLocation($entity, $location["locationX"], $location["locationY"], "5000");
                } else {
                    $content = $location;
                }
               // $content=$this->catchEntitiesFromLocation("银行","23.450306","113.494843","5000");
                break;
            case "课程表":
                $content=getCourse($user,$pass);
                break;
            case "考试时间表":
                $content=getExamTime($user,$pass);
                break;
            case "奖惩记录":
                $content=getAwardPunishment($user,$pass);
                break;
            case "考勤信息":
                $content=getAttendanceMessage($user,$pass);
                break;
            case "违规记录":
                $content=getViolationsRecord($user,$pass);
                break;
            case "考试成绩":
                $content=getPersonMessage($user,$pass);
                break;
            case "个人信息":
                $content=getPersonInfoMain($user,$pass);
                break;
            default:

          $content="请输入以下关键词:  ".'"课程表"'.'"考试时间表"'.'"奖惩记录"'.'"考勤信息"'.'"违规记录"'.'"考试成绩","个人信息"' ;
                break;
        }
        }else{
            $content="请绑定mysise账号";
        }
        if (is_array($content)) {
            $result = $this->transmitNews($object, $content);
        } else {
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }



    /**
     * 接收位置消息
     */
    private function receiveLocation($object)
    {
        include("location.php");
        $content = setLocation($object->FromUserName, (string)$object->Location_X, (string)$object->Location_Y);
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //文本消息回复
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
      <ToUserName><![CDATA[%s]]></ToUserName>
      <FromUserName><![CDATA[%s]]></FromUserName>
      <CreateTime>%s</CreateTime>
      <MsgType><![CDATA[text]]></MsgType>
      <Content><![CDATA[%s]]></Content>
      </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

////图文消息回复
//    private function transmitNews($object, $arr_item)
//    {
//   if(!is_array($arr_item))
//       return ;
//   $itemTpl="<item>
// <Title><![CDATA[%s]]></Title>
// <Description><![CDATA[%s]]></Description>
// <PicUrl><![CDATA[%s]]></PicUrl>
// <Url><![CDATA[%s]]></Url>
//</item>";
//        $item_str="";
//        foreach($arr_item as $item){
//            $item_str.=sprintf($itemTpl,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);}
//       $newsTpl="<xml>
//       <ToUserName><![CDATA[%s]]></ToUserName>
//       <FromUserName><![CDATA[%s]]></FromUserName>
//      <CreateTime>%s</CreateTime>
//      <MsgType><![CDATA[news]]></MsgType>
//      <Content><![CDATA[]]></Content>
//      <ArticlesCount>%s</ArticlesCount>
//      <Articles>$item_str</Articles>
//       </xml>";
//        $result=sprintf($newsTpl,$object->FromUserName,$object->ToUserName,time(),count($arr_item));
//     return $result;
//    }
    //debug函数
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_BAE_ENV_APPID'])){  //BAE
            require_once "BaeLog.class.php";
           $logger=BaeLog::getInstance();
            $logger->logDebug($log_content);
        }else if(isset($_SERVER['HTTP_APPNAME'])){ //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR']!="127.0.0.1"){//LOCAL
            $max_size=10000;
            $log_filename="log.xml";
            if(file_exists($log_filename)and(abs(filesize($log_filename))>$max_size)){
                unlink($log_filename);
            }
            file_put_contents($log_filename,date('H:i:s')."".$log_content."\r\n",FILE_APPEND);
        }
    }
}
?>