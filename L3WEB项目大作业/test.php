<?php
session_start();
//ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);

echo $_SESSION['usertype']."--> username:  ".$_SESSION['username'];





echo '    <button type="submit"  onclick="logout()" >退出</button>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <style>
        .container-a {
            display: flex;
            justify-content: center; /* 将容器b、c、d居中 */
        }

        .container-bcd {
            display: flex;
            align-items: center; /* 将容器b、c、d在垂直方向上居中 */
        }

        .container-b,
        .container-c,
        .container-d {
            width: 100px;
            height: 100px;
            margin: 0 10px; /* 为容器b、c、d之间增加间距 */
        }



        table {
            border: 1px solid black; /* 设置表格边框为1像素的黑色实线 */
            border-collapse: collapse; /* 合并边框，使其更加紧凑 */
        }

        th, td {
            border: 1px solid black; /* 设置表头单元格和数据单元格的边框为1像素的黑色实线 */
        }

        /*课程表样式*/
        .class_schedule {
            width: 1000px;
        }

        .tableCourse {
            table-layout: fixed;
            width: 100%;
        }

        .th_course, .td_course, .tableCourse {
            border-style: solid;
            border-width: 2px;
            border-color: rgb(156, 202, 213);
            padding: 10px 8px;
        }

        .headCourse {
            background-color: rgb(193, 235, 248);
        }

        .th_course {
            text-align: center;
        }

        .j1, .j2, .j3, .j4, .j5 , .j14 , .j15, .j16, .j17, .j18{
            background-color: aquamarine;
        }

        .j6, .j7, .j8, .j9, .j10, .j19, .j20, .j21, .j22 {
            background-color: rgb(235, 204, 163);
        }

        .j11, .j12, .j13 , .j23{
            background-color: rgb(104, 115, 197);
        }

        .course_location {
            font-weight: bold;
        }

        /*提示文字样式*/
        .course_remind {
            color: red;
            display: none;
        }
        td {
            height: 10px;
        }


    </style>
</head>
<body>

<div class="container-a">
    <div class="container-bcd">
        <div class="container-b">                        </div>
        <div class="container-c">         <h1 class="center" id="title">标题</h1>               </div>
        <div class="container-d">         </div>
    </div>
</div>


<div class="container-a">


    <div class="container-e">

        <?php

        // 定义名为 major 的数组
        $json = array();

        // 使用 AJAX 从 JSON 数据中读取元素并将其添加到 major 数组中
        $filename = 'coursinfo.json';
        $data = file_get_contents($filename);
        $json = json_decode($data, true);

        // 获取所有键--->major
        $keys = array_keys($json);


        //foreach ($json['majors'] as $m) {
        //    array_push($major, $m);
        //}

        // 将 major 数组渲染到一个下拉框中
        echo "  Major:".'<select name="major"  id="major"  onchange="majorchange()"  >';
        foreach ($keys as $m) {
            echo '<option value="' . $m . '">' . $m . '</option>';
        }
        echo '</select>';
        ?>


    </div>



    <div class="container-bcd" >
        <div class="container-b">         <button id="preWeekBtn" onclick="preweek()">preWeekBtn</button>         </div>
        <div class="container-c">         <button id="ResponEditBtn" onclick="CoordiEdit()">ResponEdit  </button></div>
        <div class="container-d">         <button id="nextWeekBtn" onclick="nextweek()">nextWeekBtn</button>      </div>
    </div>



</div>



<div class="class_schedule"></div>

</body>





<script>

    //首先获得 专业的名称：
    var major=document.getElementById("major").value;

    var weekinfoStr="week"+"1"; // 代表哪一周

    refreshPage(weekinfoStr,major);

    function nextweek(){ //让周次变为下一周

        weekinfoStr=incrementWeek(weekinfoStr);
        refreshPage(weekinfoStr,major);
    }


    function preweek(){ //让周次变为上一周

        weekinfoStr=decrementWeek(weekinfoStr);
        refreshPage(weekinfoStr,major);
    }




    function incrementWeek(weekinfoStr) { // 周次加1

        // 找到数字部分的起始位置
        const pos = weekinfoStr.indexOf("week") + 4;

        // 获取数字部分的字符串
        const numStr = weekinfoStr.substring(pos);


        // 将数字部分转换为整数并加1
        const num = parseInt(numStr) + 1;

        // 将新的数字字符串拼接到"week"字符串后面
        const newWeekinfoStr = "week" + num.toString();

        // 返回新的字符串
        return newWeekinfoStr;
    }


    function decrementWeek(weekinfoStr) { // 周次减1

        // 找到数字部分的起始位置
        const pos = weekinfoStr.indexOf("week") + 4;

        // 获取数字部分的字符串
        const numStr = weekinfoStr.substring(pos);

        if(parseInt(numStr)<=1){ // 如果在第一页就返回
            return weekinfoStr;
        }

        // 将数字部分转换为整数并加1
        const num = parseInt(numStr) - 1;

        // 将新的数字字符串拼接到"week"字符串后面
        const newWeekinfoStr = "week" + num.toString();

        // 返回新的字符串
        return newWeekinfoStr;

    }


    function refreshPage(weekinfoStr,major){ // 更具是哪一周刷新页面
        // alert( weekinfoStr);
        $.ajax({
            url: "coursinfo.json",//json文件位置，文件名
            type: "GET",//请求方式为get
            dataType: "json", //返回数据格式为json
            success: function(data) {//请求成功完成后要执行的方法

                // $.post('store_session.php', {data: data});


                console.log(data[major][weekinfoStr]);


                //alert(data.l3info.week1.length);
                // showData(data);
                createCourseTable("class_schedule",data[major][weekinfoStr], weekinfoStr);


                mergeSameTextInSameCol();

            }
        })
    }

    function createCourseTable(idName,data,weekinfoStr) {// idName 表示在哪一个标签内 生成课程表

        //将表格的标题改为课程表的第几周：
        var  title=document.getElementById("title");
        console.log(title);

        title.textContent=weekinfoStr;

        var div_ = $("." + idName);
        div_.css({margin: "30px auto"});//表格居中，添加上下边距
        var remind = '<h3 class="course_remind">课程表时间发生冲突，以下课表数据将不再准确！</h3>';
        div_.html(remind);//添加记录信息
        div_.append('<table class="tableCourse" id="table0"></table>');//添加表格
        var table_ = $(".tableCourse");

        var tr_;

        for (var i = 0; i <= 45; i++) {

            if (i == 0) {
                // 添加周信息
                table_.append('<tr class="headCourse"><th class="th_course" id="weekinfo"></th><th class="th_course">周一</th><th class="th_course">周二</th><th class="th_course">周三</th><th class="th_course">周四</th><th class="th_course">周五</th><th class="th_course">周六</th><th class="th_course">周天</th></tr>');
                var weekinfo=document.getElementById("weekinfo");
                weekinfo.textContent=data[0]; // JSON中data[0]表示哪一周
            } else {
                // 添加时段信息
                table_.append('<tr class="tr_course" id="line' + i + '"></tr>');
                tr_ = $("#line" + i);
                // <th>1</th>
                var timeTable = numToTime(i - 1); //行数转换为字符串时间
                tr_.append('<th class="th_course" id="th_1">' + timeTable + '</th>');
                for (var j = 1; j <= 7; j++) {
                    // <td id="w1_j1"></td>
                    var tdId = "line" + i + "_col" + j;
                    var classId="_col" + j;
                    tr_.append('<td class=classId     id="' + tdId + '">' + '</td>');
                    var tdObj = document.getElementById(tdId)//  获取td标签对象

                    for (var q = 1; q < data.length; q++) {
                        //data[q].type="CM";
                        var type=data[q].type;
                        var subject=data[q].subject;
                        var teacher=data[q].teacher;
                        var location=data[q].location;
                        var number=data[q].number;


                        var timeStart=data[q].timeSE[0]

                        var timeEnd=data[q].timeSE[1]
                        var date=data[q].date;//周几
                        // alert(timeStart+";"+timeEnd);

                        if( i>=timeStart&&i<=timeEnd){
                            for(var jk=0;jk<date.length;jk++){
                                if(j===date[jk]){
                                    //var color=getRandomColor();
                                    tdObj.textContent=number+" "+type+"-"+subject+"-"+location // 课表上显示的内容
                                    var tdObj = document.getElementById(tdId)// 获取td标签对象

                                    tdObj.setAttribute("type",type);
                                    tdObj.setAttribute("subject",subject);
                                    tdObj.setAttribute("location",location);
                                    tdObj.setAttribute("tstart",timeStart);
                                    tdObj.setAttribute("tend",timeEnd);
                                    tdObj.setAttribute("date",date);
                                    tdObj.setAttribute("teacher",teacher);
                                    tdObj.setAttribute("number",number);


                                    // 创建修改按钮
                                    var editBtn = document.createElement("button");
                                    editBtn.innerHTML = "修改"
                                    // 将修改按钮添加到td标签对象中
                                    tdObj.appendChild(editBtn);

                                    var aaa=tdObj.textContent;

                                    editBtn.onclick=function (){  //此处添加修改逻辑 点击按钮 可以修改科目 教师 房间
                                        // 代码中this是指被点击的按钮 this.parent是值存放按钮的表单！
                                        // console.log(this.parentNode.type);
                                        alert(this.parentNode.getAttribute("subject"));
                                        // 点击按钮时，弹出迷你悬浮弹窗
                                        var popup = window.open("", "popup", "width=200,height=600");

                                        //弹窗中创建表单
                                        var form = popup.document.createElement("form");
                                        // form.action="ResPonChangeInfo.php"; // 本页面的PHP 模块负责收集表单数据并且修改
                                        // form.method="GET";

                                        //this.parentNode是弹窗的父节点 就是原本的页面

                                        var inputCourNumber = popup.document.createElement("input");
                                        inputCourNumber.id="number";
                                        inputCourNumber.value=this.parentNode.getAttribute("number");
                                        var cournumber=this.parentNode.getAttribute("number");
                                        inputCourNumber.name=this.parentNode.getAttribute("type");


                                        var inputType = popup.document.createElement("input");
                                        inputType.id="type";
                                        inputType.value=this.parentNode.getAttribute("type");
                                        var classtype=this.parentNode.getAttribute("type");
                                        inputType.name=this.parentNode.getAttribute("type");


                                        var inputSubject = popup.document.createElement("input");
                                        inputSubject.value=this.parentNode.getAttribute("subject");
                                        inputSubject.id="subject";
                                        var subject=this.parentNode.getAttribute("subject");
                                        inputSubject.name=this.parentNode.getAttribute("subject");



                                        var inputTeacher = popup.document.createElement("input");
                                        inputTeacher.id="teacher";
                                        inputTeacher.value=this.parentNode.getAttribute("teacher");
                                        inputTeacher.name=this.parentNode.getAttribute("teacher");


                                        var inputLocation = popup.document.createElement("input");
                                        inputLocation.id="location";
                                        inputLocation.value =this.parentNode.getAttribute("location");
                                        var location =this.parentNode.getAttribute("location");
                                        inputLocation.name =this.parentNode.getAttribute("location");

                                        var inputTimeSE = popup.document.createElement("input");
                                        inputTimeSE.value=this.parentNode.getAttribute("tstart");
                                        inputTimeSE.name=this.parentNode.getAttribute("tstart");

                                        var inputTimeEnd = popup.document.createElement("input");
                                        inputTimeEnd.value=this.parentNode.getAttribute("tend");
                                        inputTimeEnd.name=this.parentNode.getAttribute("tend");


                                        var inputDate = popup.document.createElement("input");
                                        inputDate.value=this.parentNode.getAttribute("tend")
                                        inputDate.name=this.parentNode.getAttribute("tend");

                                        var submitBtn = popup.document.createElement("input");
                                        submitBtn.id="confirmModification";
                                        submitBtn.type = "submit";
                                        submitBtn.value = "提交";

                                        form.appendChild(popup.document.createTextNode("EventType"));
                                        form.appendChild(inputType);

                                        form.appendChild(popup.document.createTextNode("Subject"));
                                        form.appendChild(inputSubject);

                                        form.appendChild(popup.document.createTextNode("Teacher"));
                                        form.appendChild(inputTeacher);



                                        form.appendChild(popup.document.createTextNode("Location"));
                                        form.appendChild(inputLocation);

                                        form.appendChild(popup.document.createTextNode("courNumber"));
                                        form.appendChild(inputCourNumber);
                                        inputCourNumber.readOnly=true; // 不可修改




                                        // form.appendChild(popup.document.createTextNode("startTime: int"));
                                        // form.appendChild(inputTimeSE);
                                        //
                                        // form.appendChild(popup.document.createTextNode("EndTime: int "));
                                        // form.appendChild(inputTimeEnd);

                                        // form.appendChild(popup.document.createTextNode("date "));
                                        // form.appendChild(inputDate);

                                        form.appendChild(submitBtn);
                                        popup.document.body.appendChild(form);


                                        submitBtn.onclick = function() {

                                            var typeValue = popup.document.getElementById("type").value;
                                            var subjectValue= popup.document.getElementById("subject").value;
                                            var locationVaue=popup.document.getElementById("location").value;
                                            var numberCours=popup.document.getElementById("number").value;

                                            //document.write(typeValue+subjectValue+locationVaue+major+weekinfoStr+numberCours);

                                            // alert(typeValue+subjectValue+locationVaue+major+weekinfoStr+numberCours);

                                            $.ajax({
                                                type: 'POST',
                                                url: 'ResPonChangeInfo.php',
                                                data: {
                                                    "major":major,
                                                    "weekstr":weekinfoStr,
                                                    "newtype":typeValue,
                                                    "newsubject":subjectValue,
                                                    "newlocation":locationVaue,
                                                    "numbercours":numberCours
                                                },

                                                success: function(response) {
                                                    alert(response);
                                                    window.location.replace("ResponPage.php");
                                                },
                                                error: function(jqXHR, textStatus, errorThrown) {

                                                }
                                            });

                                        };

                                    }


                                }
                            }
                        }

                    }

                    //tdArray.push([i,j,tdObj]);
                    // 已知这里是第i行第J列 去JSON中看这个格子是否有课 有则输出

                }
            }
        }
        //console.log(tdArray);

    }

    function numToTime(num) {
        var hour = Math.floor((num) / 4) + 8; // 计算小时数
        var minute='00';
        if(num % 4 === 0)  minute='00';
        if(num % 4 === 1)  minute='15';
        if(num % 4 === 2)  minute='30';
        if(num % 4 === 3)  minute='45';
        return hour + ':' + minute; // 格式化输出时间
    }

    // 生成随机的 RGB 颜色值
    function getRandomColor() {
        var r = Math.floor(Math.random() * 256); // 随机生成 0 ~ 255 之间的整数
        var g = Math.floor(Math.random() * 256);
        var b = Math.floor(Math.random() * 256);
        return "rgb(" + r + ", " + g + ", " + b + ")";
    }




    // 在这个函数中，table 参数是要操作的表格元素对象，
    // 使用 rowCount 和 colCount 分别获取行数和列数。
    // 接着，使用两层循环遍历每一个单元格，使用 prevContent 记录之前单元格的文本内容，
    // 使用 count 记录相同单元格的个数。如果当前单元格的内容与之前相同，则将当前单元格的 rowspan 属性增加1，并将当前单元格隐藏。
    // 如果不同，则将之前单元格的 rowspan 属性设置为 count，并将之前单元格显示出来。
    function mergeSameTextInSameCol(){
        // 获取表格元素
        var table = document.getElementById("table0");
        console.log(table);

        var rowCount = table.rows.length;
        var colCount = table.rows[0].cells.length;
        var cell, prevCell;
        var prevContent, currentContent;
        for (var j = 1; j < colCount; j++) {
            prevContent = "";
            var count = 1;
            for (var i = 1; i < rowCount; i++) {
                cell = table.rows[i].cells[j];
                currentContent = cell.textContent.trim();//获取当前单元格的文本内容并去除两端的空格

                if (currentContent == prevContent && currentContent != "") {
                    count++;
                    cell.style.display = "none";

                    prevCell.rowSpan = count;

                } else {
                    count = 1;
                    cell.style.display = "";
                    cell.style.backgroundColor=getRandomColor();
                    prevContent = currentContent;
                    prevCell = cell;
                }



            }
        }


        var cells = table.getElementsByTagName("td");

        for (var i = 0; i < cells.length; i++) {
            var cell = cells[i];
            if (cell.textContent.trim() === "") {
                cell.style.backgroundColor = "white";
            }
        }

    }


    function CoordiEdit(){ // 窗口跳转
        window.location.href = "AddCourPage.php";
    }





    function sendInfoToResponChange(){};


    function majorchange(){
        major=document.getElementById("major").value;
        alert("the majour you chosed is:  "+major);

        refreshPage(weekinfoStr,major);
    }


</script>

</html>