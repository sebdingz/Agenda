// 获取课程表数据
// 创建 XMLHttpRequest 对象
// 发送 GET 请求，并返回 Promise 对象
// 创建 XMLHttpRequest 对象
// 创建一个XMLHttpRequest对象
// 创建一个XMLHttpRequest对象
//
// 创建一个 XMLHttpRequest 对象
var xhr = new XMLHttpRequest();

// 指定 JSON 文件的路径
xhr.open("GET", "coursInfo.json", true);
// 设置响应类型为 JSON
xhr.responseType = "json";

// 当请求完成时执行以下代码
var data;
xhr.onload = function(data) {
    // 如果请求成功
    if (xhr.status === 200) {
        // 解析 JSON 数据为 JavaScript 对象
        data = JSON.parse(xhr.responseText);

        // 输出 JavaScript 对象到控制台
        console.log(data);
    }
};
// 发送请求
xhr.send();








//根据课程时间、地点信息的数据解析出每一项结果
function resolveDate(date) {
    date = date.split(";");//拆分每一个课程信息
    var reDate = new Array();
    // alert("date="+date);
    //"周二6-8节,普洱善御楼1-02,"
    for (var i = 0; i < date.length - 1; i++) {
        // alert("d="+date[i]);
        var temp = date[i].split(",");
        // alert("temp="+temp);
        if (i == 0) {
            week = temp[0].substr(0, 2);
        } else {
            week = temp[0].substr(2, 2);
        }
        week = resolveWeek(week);
        var patt = /\d{1,2}[-]{1}\d{1,2}/;
        var time = patt.exec(temp[0]);
        // alert("time="+time);
        time = time[0].toString().split("-");
        reDate[i] = new ReDate(week, time[0], time[1], temp[1]);
    }
    return reDate;
}

///自定义记录解析完的上课时间、地点的类(对象)
function ReDate(week, timeBegin, timeEnd, location) {
    this.week = week;
    this.timeBegin = timeBegin;
    this.timeEnd = timeEnd;
    this.location = location;
}

//根据周信息解析出对应代码
function resolveWeek(week) {
    switch (week) {
        case "周一": week = "1"; break;
        case "周二": week = "2"; break;
        case "周三": week = "3"; break;
        case "周四": week = "4"; break;
        case "周五": week = "5"; break;
        case "周六": week = "6"; break;
        case "周日": week = "7"; break;
    }
    return week;
}


var record = "";//记录课程冲突信息

//把对应数据添加到课程表里
//课程代码，课程名称，教师，周，开始时间，结束时间，位置
//问题：可能存在课程冲突情况
function addHtml(id, lesson, teacher, date) {
    var temp;
    for (var i = 0; i < date.length; i++) {
        temp = date[i];
        var l = $("#w" + temp.week + "_j" + temp.timeBegin);
        if (l.length > 0 && l[0].innerText == "") {
            //在表格中加入课程数据
            l.html("<span class='course_id'>" + id + "</span> <span class='course_lesson'>" +
                lesson + "</span><br><span class='course_teacher'>"
                + teacher + "</span><br>" + "<span class='course_location' >" +
                temp.location + "</span>"
            );
            //alert(l.html());
        } else {
            //说明该位置已经被其它课程占用，在冲突位置或者被合并位置
            //循环往上找到冲突信息所在位置
            var temp2;
            for (var j = temp.timeEnd; j > 0; j--) {
                temp2 = $("#w" + temp.week + "_j" + j);
                if (temp2[0]!=null&&temp2.text() != "") {
                    //添加原课程冲突信息
                    record += "<br>【《 " + temp2.children('.course_id').text() + " " + temp2.children('.course_lesson').text() + " " + temp2.children('.course_teacher').text() + " 》";
                    //添加现课程冲突信息
                    record += "和《 " + id + " " + lesson + " " + teacher + " 》】";
                }
            }
            //退出该层循环
            continue;
        }

        var s = temp.timeEnd - temp.timeBegin;
        /* alert(s); */
        //合并单元格
        l.attr('rowspan', s + 1);
        //alert(s);
        var object;//元素对象
        var token = false;//标记合并单元格时是否会有冲突：【false】没有时间冲突，【true】有时间冲突
        for (var j = 1; j <= s; j++) {
            object = $("#w" + temp.week + "_j" + (Number.parseInt(temp.timeBegin) + j));
            //该元素要合并的单元格所在的元素存在 并且元素的内容为空，则不冲突
            if (object.length > 0 && object[0].innerText == "") {
                object.remove();
            } else if (object.length > 0) {
                //若要合并的元素存在，但是内容不为空，则也冲突
                token =true;
                //记录冲突课程信息
                record += "<br>【《 " + object.children('.course_id').text() + " " + object.children('.course_lesson').text() + " " + object.children('.course_teacher').text() + " 》";
                //添加现课程冲突信息
                record += "《 " + id + " " + lesson + " " + teacher + " 》】";
                object.remove();
            }
            else {
                //上面删除冲突的信息后
                //可能存在单元格不存在的情况
                token = true;
            }
            //alert("删除的是："+"#w"+temp.week+"_j"+(Number.parseInt(temp.timeBegin)+j));
        }
        //有冲突则记录，并删除冲突的信息
        //这样删除有数据的单元格可能存在空缺的单元格
        //所有需要把空缺的单元格找回
        if (token==true) {
            //把丢失的单元格补充回来
            //往下循环看丢失的格子并找回
            var end = Number.parseInt(temp.timeEnd);
            for (var j = 13; j > end; j--) {
                object = $("#w" + temp.week + "_j" + (Number.parseInt(temp.timeEnd) + j));
                if (object.length == 0) {
                    var obj;
                    //循环找到丢失元素之前的元素
                    for (var k = Number.parseInt(week); k > 0; k--) {
                        obj = $("#w" + k + "_j" + (Number.parseInt(temp.timeEnd) + j));
                        //若找到则在元素之后添加丢失的元素，没找到则继续循环向前找
                        if (obj.length > 0) {
                            obj.after("<td id='w" + temp.week + "_j" + (Number.parseInt(temp.timeEnd) + j) + "'></td>");
                            break;
                        }
                    }
                } else {
                    //若该元素之下的第一个
                    break;
                }
            }
            token = false;
        }
    }
}


// var data = [
//     {
//         XKBH: "R0101",
//         KCMC: "毛泽东思想和中国特色社会主义理论体系概论",
//         JSXM: "XXX",
//         SJDD: "周一3-5节,XX楼1-17;..周四6-7节,XX楼1-17;",
//     },
//     {
//         XKBH: "R0199",
//         KCMC: "管理信息系统",
//         JSXM: "XXX",
//         SJDD: "周一6-7节,XXX楼1-02;..周四8-9节,XXX楼3-01;..周五1-2节,XX楼3-01;",
//     }
// ];
//根据json对象解析出对应的数据，并在表格中展示
function classSchedule(data) {
    //先清空record
    record = "";
    var temp;//课程对象
    var id;//课程id
    var lesson;//课程名称
    var teacher;//教师
    var date;//课程时间
    for (var i = 0; i < data.length; i++) {
        temp = data[i];
        id = temp.XKBH;
        lesson = temp.KCMC;
        teacher = temp.JSXM;
        date =  resolveDate(temp.SJDD.toString());
        addHtml(id, lesson, teacher, date);
    }
    if (record != "") {
        $(".course_remind").css("display", "block");
    }
    //把冲突的信息记录返回
    return record;
};

function createCourseTable(idName,data) {
    var div_ = $("." + idName);
    div_.css({ margin:"30px auto" });//表格居中，添加上下边距
    var remind = '<h3 class="course_remind">课程表时间发生冲突，以下课表数据将不再准确！</h3>';
    div_.html(remind);//添加记录信息
    div_.append('<table class="tableCourse"></table>');//添加表格
    var table_ = $(".tableCourse");
    var tr_;
    for (var i = 0; i <= 13; i++) {
        if (i == 0) {
            //添加周信息
            table_.append('<tr class="headCourse"><th class="th_course"></th><th class="th_course">周一</th><th class="th_course">周二</th><th class="th_course">周三</th><th class="th_course">周四</th><th class="th_course">周五</th><th class="th_course">周六</th><th class="th_course">周天</th></tr >');
        } else {
            //添加时段信息
            //<tr class="j1"></tr>
            table_.append('<tr class="j' + i + '"></tr>');
            tr_ = $(".j" + i);
            //<th>1</th>
            tr_.append('<th class="th_course">' + i + '</th>');
            for (var j = 1; j <= 7; j++) {
                //<td id="w1_j1"></td>
                tr_.append('<td id="w' + j + '_j' + i + '" class="td_course"></td>');
            }
        }
    }

    return classSchedule(data);
}

createCourseTable("class_schedule",data);