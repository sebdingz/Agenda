<?php
session_start();
//ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);

echo $_SESSION['usertype']."--> username:  ".$_SESSION['username']."--> major".$_SESSION['major'];
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
            margin: 0 auto; /* 将表格居中显示 */
            display: table; /* 将元素显示为表格 */
            border-collapse: collapse; /* 合并边框 */
        }

        .class_schedule td, .class_schedule th {
            padding: 10px; /* 设置单元格内边距 */
            border: 1px solid black; /* 设置单元格边框 */
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
        <div class="container-c">         <h1 class="center" id="title">标题!!!!</h1>               </div>
        <div class="container-d">         </div>
    </div>
</div>

<div class="container-a" >
    <div class="container-bcd">
        <div class="container-b">         <button id="preWeekBtn" onclick="preweek()">preWeekBtn</button>                </div>


        <div class="container-c">
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
            echo "".'<select name="major"  id="major"  onchange="majorchange()"  >';
            foreach ($keys as $m) {
                echo '<option value="' . $m . '">' . $m . '</option>';
            }
            echo '</select>';
            ?>
        </div>

        <div class="container-d">         <button id="nextWeekBtn" onclick="nextweek()">nextWeekBtn</button>   </div>


    </div>
</div>

<div class="class_schedule"></div>

</body>
<script>



    var weekinfoStr="week"+"1"; // 代表哪一周

    var major=document.getElementById("major").value;

    refreshPage(weekinfoStr,major);

    function nextweek(){

        weekinfoStr=incrementWeek(weekinfoStr);
        refreshPage(weekinfoStr,major);
    }


    function preweek(){

        weekinfoStr=decrementWeek(weekinfoStr);
        refreshPage(weekinfoStr,major);
    }


    function incrementWeek(weekinfoStr) {

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

    function decrementWeek(weekinfoStr) {

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

    function refreshPage(weekinfoStr,major){

        // alert( weekinfoStr);
        $.ajax({
            url: "coursinfo.json",//json文件位置，文件名
            type: "GET",//请求方式为get
            dataType: "json", //返回数据格式为json
            success: function(data) {//请求成功完成后要执行的方法

                console.log(data[major][weekinfoStr]);

                //alert(data.l3info.week1.length);
                // showData(data);
                createCourseTable("class_schedule",data[major][weekinfoStr], weekinfoStr);


                mergeSameTextInSameCol();

                // for (var j = 1; j <= 7; j++) {
                //     var classId="_col" + j;
                //     console.log($('#classId'));
                // }
                //
                //console.log(TimeToNum("10:30"));
                // 获取了data数组 之后绘制表格

            }
        })

    }

    // 在idName这个标签中，根据data[weekinfoStr] 创建对应周的数据

    function createCourseTable(idName,data,weekinfoStr) {
        //将表格的标题改为课程表的第几周：
        var  title=document.getElementById("title");
        console.log(title);
        title.textContent=weekinfoStr;
        title.style.textAlign = "center";

        var div_tablelocation = $("." + idName); // 获取表格所在位置的标签
        div_tablelocation.css({margin: "30px auto"});//表格居中，添加上下边距
        var remind = '<h3 class="course_remind">课程表时间发生冲突，以下课表数据将不再准确！</h3>';
        div_tablelocation.html(remind);//添加记录信息

        div_tablelocation.append('<table class="tableCourse" id="table0"></table>');//添加表格

        var table_ = $(".tableCourse");// 获取表格

        var tr_line;


        for (var i = 0; i <= 45; i++) { // i=45 代表我们课表的时间持续时间

            if (i == 0) {
                // 添加表头信息（周几） 与 分组信息
                table_.append('<tr class="headCourse"><th class="th_course" id="weekinfo" colspan="3" style="width: 300px; height: 50px;"></th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周一</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周二</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周三</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周四</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周五</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周六</th><th class="th_course" colspan="3" style="width: 300px; height: 50px;">周天</th></tr>');
                table_.append('<tr><td colspan="3">   GROUP INFORMATION</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td><td colspan="1">G1</td><td colspan="1">G2</td><td colspan="1">G3</td></tr>');

                var weekinfo=document.getElementById("weekinfo");
                weekinfo.textContent=weekinfoStr; // 显示这一周的信息

            } else {
                // 添加时段信息
                table_.append('<tr class="tr_course" id="line' + i + '"></tr>'); //行标签代表这是哪一行 每一行是一个时段15分钟

                tr_line = $("#line" + i); // 在每一行里添加列

                // <th>1</th>
                var timeTable = numToTime(i - 1); // 行数转换为字符串时间

                tr_line.append('<th class="th_course" id="th_1" colspan="3">' + timeTable + '</th>');

                for (var j = 1; j <= 21; j++) { //这个循环代表每一行的每一列中要绘制什么
                    // <td id="w1_j1"></td>
                    var tdId = "line" + i + "_col" + j;
                    var classId="_col" + j;
                    tr_line.append('<td class=classId  colspan="1"   id="' + tdId + '">' + '</td>'); // 每一行都放进去21个单元格 并且给他表上ID
                    var tdObj = document.getElementById(tdId)// 获取每行的每列单元格，在里面添加课程信息惹

                    for (var q = 0; q < data.length; q++) {
                        var type=data[q].type;
                        var subject=data[q].subject;
                        var teacher=data[q].teacher;
                        var location=data[q].location;

                        var timeStart=data[q].timeSE[0]

                        var timeEnd=data[q].timeSE[1]
                        var date=data[q].date;//周几

                        var group=data[q].group;

                        var number=data[q].number;


                        if( i>=timeStart&&i<=timeEnd){ // 行数在时间范围内
                            for(var jk=0;jk<date.length;jk++){
                                if(j===(3*(date[jk]-1)+group)){
                                    //var color=getRandomColor();
                                    tdObj.textContent =
                                        "Type:" + type + "\n" +
                                        "Subject: " + subject + "\n" +
                                        "Teacher:" + teacher + "\n" +
                                        "Location:" + location;

                                    // 文字下面有修改按钮

                                    tdObj.setAttribute("type",type);
                                    tdObj.setAttribute("subject",subject);
                                    tdObj.setAttribute("teacher",teacher);
                                    tdObj.setAttribute("location",location);
                                    tdObj.setAttribute("tstart",timeStart);
                                    tdObj.setAttribute("tend",timeEnd);
                                    tdObj.setAttribute("date",date);
                                    tdObj.setAttribute("number",number);


// 创建修改按钮
                                    var editBtn = document.createElement("button");
                                    editBtn.innerHTML = "Modify"
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
                                            var teacher=popup.document.getElementById("teacher").value;
                                            var numberCours=popup.document.getElementById("number").value;

                                            document.write(typeValue+subjectValue+locationVaue+major+weekinfoStr+numberCours+teacher);

                                            alert(typeValue+subjectValue+locationVaue+major+weekinfoStr+numberCours+teacher);

                                            $.ajax({
                                                type: 'POST',
                                                url: 'ResPonChangeInfo.php',
                                                data: {
                                                    "major":major,
                                                    "weekstr":weekinfoStr,
                                                    "newtype":typeValue,
                                                    "newsubject":subjectValue,
                                                    "newlocation":locationVaue,
                                                    "newteacher":teacher,
                                                    "numbercours":numberCours
                                                },

                                                success: function(response) {
                                                    alert(response);
                                                    window.location.replace("ResponPage.php");

                                                },
                                                error: function(jqXHR, textStatus, errorThrown) {
                                                        alert("failed");
                                                }
                                            });
                                        };

                                    }





                                }
                            }
                        }

                        // alert(timeStart+";"+timeEnd);




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
        var r = Math.floor(Math.random() * 120 + 136); // 136 ~ 255
        var g = Math.floor(Math.random() * 120 + 136); // 136 ~ 255
        var b = Math.floor(Math.random() * 120 + 136); // 136 ~ 255

        // 将RGB颜色值转换为CSS颜色格式
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

        var colCount = table.rows[12].cells.length;


        var cell, prevCell;
        var prevContent, currentContent;


        // 合并同一行中相邻的相同text的单元格
        for (var i = 1; i < rowCount; i++) {
            prevContent = "";
            var count = 1;

            for (var j = 1; j < colCount; j++) { // 遍历行中的单元格
                cell = table.rows[i].cells[j];
                currentContent = cell.textContent.trim();

                if (currentContent == prevContent && currentContent != "") {
                    count++;
                    cell.style.display = "none";

                    prevCell.colSpan = count; // 合并相同text的单元格

                } else {
                    count = 1;
                    cell.style.display = "";

                    prevContent = currentContent;
                    prevCell = cell;
                }
            }
        }



        // 合并同一列上相同单元格
        for (var j = 1; j < colCount; j++) {

            prevContent = "";
            var count = 1;

            for (var i = 1; i < rowCount; i++) {  // 遍历表格 cell[i][j]

                cell = table.rows[i].cells[j];
                currentContent = cell.textContent.trim();//获取当前单元格的文本内容并去除两端的空格

                if (currentContent == prevContent && currentContent != "") {

                    count++;
                    cell.style.display = "none"; // 除了第一格以外的相同内容表格text隐藏

                    prevCell.rowSpan = count; // 第一个单元格的合并列参数个数变为count

                } else {
                    count = 1;
                    cell.style.display = "";
                    prevContent = currentContent;
                    prevCell = cell;
                }
            }
        }


        var cells = table.getElementsByTagName("td");

        for (var i = 0; i < cells.length; i++) { //将没有文字的格子颜色设置为白色
            var cell = cells[i];
            if (cell.textContent.trim() === "") {
                cell.style.backgroundColor = "white";
            }
            else if (cell.textContent.trim() === "G1") {
                cell.style.backgroundColor = "SkyBlue";
            }
            else if (cell.textContent.trim() === "G2") {
                cell.style.backgroundColor = "Lavender";
            }
            else if(cell.textContent.trim() === "G3") {
                cell.style.backgroundColor = "Honeydew";
            }
            else{
                cell.style.backgroundColor = getRandomColor();
            }
        }


        var table = document.getElementById("table0");
        var cells = table.getElementsByTagName("td");

        for (var i = 0; i < cells.length; i++) {
            cells[i].style.textAlign = "center";
            cells[i].style.verticalAlign = "middle";
        }

    }


    function  logout(){

        window.location.href = 'index.php';
    }


    function majorchange(){
        major=document.getElementById("major").value;
        alert("the majour you chosed is:  "+major);
        refreshPage(weekinfoStr,major);
    }



</script>

</html>