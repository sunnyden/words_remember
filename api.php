<?php

//echo "dafddfd";
//$type=$_GET['type'];
/*
WordsRemember Internet API Ver1.2-20140828 Author:Deng Haoqing
Copyright denghaoqing.com 2011-2014 All rights reserved.
Bug report :admin@denghaoqing.com
*/
//parse_str($_SERVER["QUERY_STRING"]);
//echo "$type";

$type=$_GET['type'];
$word=$_POST['word'];
$explan=$_POST['explan'];
$timestamp=$_POST['timestamp'];
$is_right=$_POST['is_right'];
//echo $type;
/*
EMERGENCY ONLY
$word=$_POST['word'];
$explan=$_POST['explan'];
$timestamp=$_POST['timestamp'];
EMERGENCY ONLY*/
//$con = mysql_connect("23.110.56.109","a0329114508","97409882");
$con = mysql_connect("localhost","a0822213330","32445174");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
if($type==1){
//Record(Create) API (type is 1)
/*
API 功能1：单词录入
*/

$sql = "INSERT INTO `a0822213330`.`WordsList` (`id`, `words`, `chn`, `time_create`, `time_read`, `count_right`, `count_total`) VALUES (NULL, '${word}', '${explan}', '${timestamp}', '${timestamp}', '0', '0');";//REM:database name and table name remodify it when upload
mysql_query($sql,$con);
echo mysql_error();
echo "hi";
}elseif($type==2){
/*
API功能2：
获取当前单词条目数量
*/
mysql_select_db("a0822213330", $con);
$result = mysql_query("SELECT COUNT(*) AS total FROM WordsList");
while($row = mysql_fetch_array($result))
  {
  echo $row['total'];
  }

}elseif($type==3){
/*
API功能3：获取当前数据库值，并以json格式返回
*/
mysql_select_db("a0822213330", $con);//数据库名，别忘了。。
$result = mysql_query("SELECT COUNT(*) AS total FROM WordsList");
$total = "";
while($cc = mysql_fetch_array($result))  //此处获取数据库储存条目书目
  {
  $total=$cc['total'];
  }
//echo"{\"count\":\"${total}\",\"words\":{";//此处为json头
echo "{\"count\":${total},\"words\":{";//此处为json头
$result2 = mysql_query("SELECT * FROM WordsList");
$i = 0;
//start loop

while($data = mysql_fetch_array($result2))  //此处获取数据库储存条目书目
  {
$i=$i+1;
//这里获取详细数据，输出数据模型按照项目文档中json相关格式弄（按照循环格式）
if($i==1){}else{
echo ",";   //json逗号分隔符
}
echo "\"${i}\":{\"word\":\"$data[words]\",\"explain\":\"$data[chn]\",\"createdate\":\"$data[time_create]\",\"readdate\":\"$data[time_read]\",\"right\":\"$data[count_right]\",\"totcount\":\"$data[count_total]\"}";


//echo "ok"
}
//loop
echo "}}";//json生成完毕。
}elseif($type==4){
/*
API #4:
use:$timestamp
use:$type
use:$word
@type4
to update SQL
*/
//UPDATE `dbWordsRem`.`WordsList` SET `time_read` = '2014-08-04 00:00:00' WHERE `WordsList`.`id` = 1;
$sql = "UPDATE `a0822213330`.`WordsList` SET `time_read` = '${timestamp}' WHERE `WordsList`.`words` = '${word}';";//REM:database name and table name remodify it when upload
mysql_query($sql,$con);
//echo "hi";
//echo mysql_error();
}elseif($type==5){
/*
API.#5：
删除某个记录
use:$type
use:$word
DELETE FROM `a0822213330`.`wordslist` WHERE `wordslist`.`words` = '${word}'
*/
$sql = "DELETE FROM `a0822213330`.`wordslist` WHERE `wordslist`.`words` = '${word}';";//REM:database name and table name remodify it when upload
mysql_query($sql,$con);


}elseif($type==6){
//select *,count_right/count_total as rate from wordslist order by rate desc;
//
/*
API.#6
常错词检测录入专用
*/
mysql_select_db("a0822213330", $con);//数据库名，别忘了。。//SELECT COUNT(*) AS total FROM WordsList
$result = mysql_query("SELECT COUNT(*) AS total FROM WordsList");
$total = "";
while($cc = mysql_fetch_array($result))  //此处获取数据库储存条目书目
  {
  $total=$cc['total'];
  }
//echo"{\"count\":\"${total}\",\"words\":{";//此处为json头
echo "{\"count\":${total},\"words\":{";//此处为json头
$result2 = mysql_query("select *,count_right/count_total as rate from wordslist order by rate");
$i = 0;
//start loop

while($data = mysql_fetch_array($result2))  //此处获取数据库储存条目书目
  {
$i=$i+1;
//这里获取详细数据，输出数据模型按照项目文档中json相关格式弄（按照循环格式）
if($i==1){}else{
echo ",";   //json逗号分隔符
}
echo "\"${i}\":{\"word\":\"$data[words]\",\"explain\":\"$data[chn]\",\"createdate\":\"$data[time_create]\",\"readdate\":\"$data[time_read]\",\"right\":\"$data[count_right]\",\"totcount\":\"$data[count_total]\"}";


//echo "ok"
}
//loop
echo "}}";//json生成完毕。
}elseif($type==7){
/*
API #7
单词对错更新
$is_right=$_POST['is_right'];
$word=$_POST['word'];
SQL:Update
*/
echo "ok";
if($is_right==1){
//Right
$sql = "UPDATE `a0822213330`.`WordsList` SET `count_total` = count_total +1 ,`count_right`=count_right +1 WHERE `WordsList`.`words` = '${word}';";//REM:right db update
//SET `count_total` = count_total +1 ,`count_right`=count_right +1 WHERE `WordsList`.`words` = '${word}';
echo "right";
}elseif($is_right==2){
//Wrong
//echo "hi";
echo "Wrong";
$sql = "UPDATE `a0822213330`.`WordsList` SET `count_total` = count_total +1 WHERE `WordsList`.`words` = '${word}';";
}
mysql_query($sql,$con);
$updatetime = "UPDATE `a0822213330`.`WordsList` SET `time_read` = '${timestamp}' WHERE `WordsList`.`words` = '${word}';";//REM:database name and table name remodify it when upload
mysql_query($updatetime,$con);
}elseif($type==8){
//echo $type;
/*
API #8
数据统计功能
needs SQL :select
$type
*/
//select *,count_right/count_total as rate from wordslist order by rate limit 1,10; top 10
//select count(*) from wordslist;
//select time_read from wordslist order by time_read desc limit 1,1; 
//select sum(count_total) from wordslist; 总次数
//select sum(count_right)/sum(count_total)*100 from wordslist;
//time_read
//$sql=""；
//
/*
while($data = mysql_fetch_array($result2))  //此处获取数据库储存条目书目
  {
$i=$i+1;
//这里获取详细数据，输出数据模型按照项目文档中json相关格式弄（按照循环格式）
if($i==1){}else{
echo ",";   //json逗号分隔符
}
echo "\"${i}\":{\"word\":\"$data[words]\",\"explain\":\"$data[chn]\",\"createdate\":\"$data[time_create]\",\"readdate\":\"$data[time_read]\",\"right\":\"$data[count_right]\",\"totcount\":\"$data[count_total]\"}";*/
$sql_get_fen="select round(sum(count_right)/sum(count_total)*100) as result from wordslist;";
$sql_count_rec="select count(*) as result from wordslist;";
$sql_time_last_read="select time_read as result from wordslist order by time_read desc limit 1,1; ";
$sql_count_test="select sum(count_total) as result from wordslist;";
$sql_count_top10="select *,count_right/count_total as rate from wordslist order by rate limit 1,10;";
mysql_select_db("a0822213330", $con);
//$result_fen = mysql_query($sql_get_fen);

/*
while($cc = mysql_fetch_array($result))  //此处获取数据库储存条目书目
  {
  $total=$cc['total'];
  }
*/
//mysql_fetch_array(mysql_query($sql_get_fen))['result'];
echo "{";
echo "\"aver\":";
$res1 = mysql_fetch_array(mysql_query($sql_get_fen));
echo $res1['result'];
echo ",";
echo "\"record\":";
$res1 = mysql_fetch_array(mysql_query($sql_count_rec));
echo $res1['result'];
echo ",";
echo "\"time_last\":\"";
$res1 = mysql_fetch_array(mysql_query($sql_time_last_read));
echo $res1['result'];
echo "\",";
echo "\"test_count\":";
$res1 = mysql_fetch_array(mysql_query($sql_count_test));
echo $res1['result'];

echo ",";
$i=0;
$arrayres=mysql_query($sql_count_top10);
while($res1 = mysql_fetch_array($arrayres)){
$i=$i+1;
echo "\"${i}\":\"";
echo $res1['words'];
echo "\"";
if($i<>10){echo ",";}
}
echo "}";
}elseif($type==9){
/*
API #9
单词计数重置
$type $words
*/

$updatecount = "UPDATE `a0822213330`.`WordsList` SET `count_right` = '0',`count_total` = '0' WHERE `WordsList`.`words` = '${word}';";//REM:database name and table name remodify it when upload
mysql_query($updatecount,$con);
}elseif($type==10){
$updatecount = "UPDATE `a0822213330`.`WordsList` SET `count_right` = '0',`count_total` = '0';";
mysql_query($updatecount,$con);
echo "this is 10";
}elseif($type==11){
$deleteall = "DELETE FROM `a0822213330`.`wordslist`;";
mysql_query($deleteall,$con);
echo "hi";
}

mysql_close($con);
?>
