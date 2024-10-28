<?php
session_start();

$newmajor = $_POST['newmajor'];

$json_data = file_get_contents('coursinfo.json');
$coursinfo = json_decode($json_data, true);

// 如果新专业不存在，则添加
if (!array_key_exists($newmajor, $coursinfo)) {
    $coursinfo[$newmajor] = array();
}

$json_data = json_encode($coursinfo, JSON_PRETTY_PRINT);

if (file_put_contents('coursinfo.json', $json_data) !== false) {
    echo "Successfully added new major: $newmajor";
} else {
    echo "Failed to add new major";
}
?>
