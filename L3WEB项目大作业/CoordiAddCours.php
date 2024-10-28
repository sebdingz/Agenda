<?php
session_start();

$major = $_POST['major'];
$week = $_POST['week'];
$timeS = $_POST['timeSE'];
$timeEnd = $_POST['timeEnd'];
$timeSE =array((int) $timeS, (int) $timeEnd);

$jour = $_POST['date'];

$date = array();
array_push($date, (int) $jour);


$group = (int)$_POST['group'];


$json_data = file_get_contents('coursinfo.json');

$coursinfo = json_decode($json_data, true);

$isConflict=false;

foreach ($coursinfo[$major][$week] as $cour) {

    if($cour["date"]===(int)$jour && $cour["group"]===$group){

                $timeSEexist = $cour["timeSE"];

        if(  $timeSE[0]< $timeSEexist[0]&&$timeSE[1]>= $timeSEexist[0]&&$timeSE[1]<=$timeSEexist[1] ){
            $isConflict=true;
            break;
        }
    }

//    foreach ($courses as $course) {
//        $timeSEexist = $course["timeSE"];
////        echo $timeSE[0]; // 输出开始时间
////        echo $timeSE[1]; // 输出结束时间
//
//        if(   $timeSE[1]>= $timeSEexist[0]&&$timeSE[1]<=$timeSEexist[1] ){
//            $isConflict=true;
//            break;
//        }
//    }
}


    // do something

    // 专业存在


if($isConflict===false){
    if (array_key_exists($major, $coursinfo)){
        // 这一周存在
        if (array_key_exists($week, $coursinfo[$major])) {
            //遍历这一周的课程，看我们要添加的课程是否与已经存在的课程有冲突
            $new_class = array("type" => "undefined", "subject" => "undefined", "teacher" => "undefined", "location" => "undefined", "timeSE" => $timeSE, "date" => $date, "number" => count($coursinfo[$major][$week]), "group" => $group);
            $thisarray = $coursinfo[$major][$week];
            array_push($thisarray, $new_class);
            $coursinfo[$major][$week] = $thisarray;
            $json_data = json_encode($coursinfo, JSON_PRETTY_PRINT);
        }
    }

}


if (file_put_contents('coursinfo.json', $json_data) !== false) {
    echo '<script>
                alert("sucseesfly inset");
                window.location.href = "CoordiPage.php";
                </script>';
} else {
    echo '<script>
                alert("insert failed");
                window.location.href = "CoordiPage.php";
                </script>';
}

//print_r($coursinfo);
// 要插入的值













































