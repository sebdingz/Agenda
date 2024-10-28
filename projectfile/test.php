
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="JQUERY.js"></script>
</head>

<body>
<div class="class_schedule">
    <table></table>


</div>
</body>


<script >

    $(document).ready(function() {
        // 获取所有表格元素
        var tables = $("table");

        // 在控制台中输出表格元素数量
        console.log("Number of tables: " + tables.length);

        // 遍历所有表格元素并输出到控制台
        tables.each(function() {
            console.log($(this));
        });



    });

</script>



</html>


