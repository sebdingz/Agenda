<?php
session_start();

$major = $_POST['major'];
$week = $_POST['week'];


$json_data = file_get_contents('coursinfo.json');

$coursinfo = json_decode($json_data, true);



//看看一共有几周存在了

$weekCount = count($coursinfo[$major]);
$newweek = 'week' . ($weekCount+1);

$coursinfo[$major][$newweek]=array();

$coursinfo[$major]['week2'] = array();

$json_data = json_encode($coursinfo, JSON_PRETTY_PRINT);


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






































