<?php

$json_data = '{
    "l3info": {
        "week1": [
            "2023-9-1",
            {
                "type": "CM",
                "subject": "web",
                "teacher": "zoe",
                "location": "B002",
                "timeSE": [0, 8],
                "date": [1]
            },
            {
                "type": "TD",
                "subject": "BD",
                "teacher": "zoe",
                "location": "B002",
                "timeSE": [9, 18],
                "date": [2]
            },   
                        {
                "type": "TD",
                "subject": "BD",
                "teacher": "zoe",
                "location": "B002",
                "timeSE": [1, 17],
                "date": [3]
            },
            {
                "type": "TP",
                "subject": "english",
                "teacher": "zoe",
                "location": "B002",
                "timeSE": [1, 17],
                "date": [3]
            }
        ]
    }
}';



$data = json_decode($json_data);
$subject = 'l3info';
$subject_hours = array();

if (isset($data->{$subject})) {
    foreach ($data->{$subject} as $week => $week_data) {
        for ($i = 1; $i < count($week_data); $i++) {
            $item = $week_data[$i];
            $subject_name = $item->subject;
            $hours = $item->timeSE[1] - $item->timeSE[0] + 1;

            if (isset($subject_hours[$subject_name])) {
                $subject_hours[$subject_name] += $hours;
            } else {
                $subject_hours[$subject_name] = $hours;
            }
        }
    }
}

// 结果存储为关联数组
$result = array($subject => $subject_hours);

// 打印关联数组
print_r($result);

?>






<!DOCTYPE html>
<html>
<head>
    <title>JSON 数据柱状图</title>
    <!-- 在头部添加 jQuery 的链接 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
</head>
<body>
<h1>JSON 数据柱状图</h1>
<div id="canvas-container"></div>

<script>
    //读取课程JSON数据



    $.ajax({
        url: "coursinfo.json",//json文件位置，文件名
        type: "GET",//请求方式为get
        dataType: "json", //返回数据格式为json
        success: function(classdata) {


            //console.log(classdata);
            // 定义 JSON 数据
            var jsonData = {
                "专业 A": {
                    "课程 1": 50,
                    "课程 2": 30,
                    "课程 3": 70
                },
                "专业 B": {
                    "课程 1": 60,
                    "课程 2": 40,
                    "课程 3": 80
                },
                "专业 C": {
                    "课程 1": 70,
                    "课程 2": 50,
                    "课程 3": 90,
                    "课程 4": 90,
                    "课程 5": 90,
                    "课程 6": 90,
                }
            };

            // 获取画布容器
            var canvasContainer = document.getElementById('canvas-container');

            // 获取 JSON 数据中的专业数量
            var numMajors = Object.keys(jsonData).length;

            // 创建相应数量的画布，并显示柱状图
            for (var i = 0; i < numMajors; i++) {
                // 创建画布元素
                var canvasElem = document.createElement('canvas');
                canvasElem.id = 'canvas-' + i;
                canvasElem.width = 400;
                canvasElem.height = 400;
                canvasContainer.appendChild(canvasElem);

                // 获取当前专业名称和课程数据
                var majorName = Object.keys(jsonData)[i];
                var courseData = Object.values(jsonData)[i];

                // 将课程数据转换为数组形式
                var courseNames = Object.keys(courseData);
                var courseHours = Object.values(courseData);

                // 创建柱状图
                new Chart(canvasElem, {
                    type: 'bar',
                    data: {
                        labels: courseNames,
                        datasets: [{
                            label: '课时',
                            data: courseHours,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)'
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: majorName
                        },
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }

        }
    })



</script>
</body>
</html>













