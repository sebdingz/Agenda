<?php
session_start();

//$major ="l3info";
//$weekstr ="week1";
//$numbercours=1;
//// 从文件中读取JSON数据
//
//$newtype = "ABA";
//$newsubject = "AAA";
//$newlocation ="AAA";

$major = $_POST['major'];
$weekstr = $_POST['weekstr'];
$numbercours = $_POST['numbercours'];


$newtype = $_POST['newtype'];
$newsubject = $_POST['newsubject'];
$newlocation = $_POST['newlocation'];

$newteacher = $_POST['newteacher'];


// 从文件中读取JSON数据
$json_data = file_get_contents('coursinfo.json');

// 解析JSON数据
$coursinfo = json_decode($json_data, true);


// 获取要修改的课程
$week = $coursinfo[$major][$weekstr]; //这里是一个数组

$course = $week[$numbercours];// 对应哪一节课{"type": CM ,………………}

// 更新课程信息
$course['type'] = $newtype;
$course['subject'] = $newsubject;
$course['location'] = $newlocation;
$course['teacher'] = $newteacher;

$week[$numbercours]=$course;
$coursinfo[$major][$weekstr]=$week;


$json_data = json_encode($coursinfo, JSON_PRETTY_PRINT);

echo $json_data;

if (file_put_contents('coursinfo.json', $json_data) !== false) {
    echo 'false';
} else {
    echo 'true';
}