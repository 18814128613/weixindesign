<?php
define("DEBUG_MODE",false);
catchEntitiesFromLocation("银行","23.450306","113.494843","5000");
function catchEntitiesFromLocation($entity,$x,$y,$radius)
{
    //注意ak替换为自己申请的值
    $url= "http://api.map.baidu.com/place/v2/search?ak=Sr9GRvZI3u7Xgrm9DpTOB9FquogAlaRc&output=json&query=".$entity."&page_size=5&page_num=0&scope=2&location=".$x.",".$y."&radius=".$radius."&filter=sort_name:distance";
       $ch=curl_init();
       curl_setopt($ch,CURLOPT_URL,$url);

       curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      $output=curl_exec($ch);
      $data=json_decode($output,true);
     if($data['status']!=0){
         return $data['message'];
     }

     $results=$data['results'];
    if(count($results)==0){
        return "附近没有找到".$entity;
    }
    $shopArray=array();
    $shopArray[]=array("Title"=>"附近的".$entity,"Description"=>"","PicUrl"=>"","Url"=>"");
    for($i=0;$i<count($results);$i++){
        $shopArray[]=array(
            "Title"=>"{".$results[$i]['name']."}<".$results[$i]['detail_info']['distance']."米>\n".$results[$i]['address'].
                (isset($results[$i]['telephone'])?"\n".$results[$i]['telephone']:""),
             "Description"=>"",
            "PicUrl"=>"",
            "Url"=>(isset($results[$i]['detail_info']['detail_url'])?($results[$i]['detail_info']['detail_url']):"")
        );
    }
    print_r($shopArray);
    return $shopArray;
}
?>