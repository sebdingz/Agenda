<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD courses</title>
    <style>
        /* 添加CSS样式 */
        .form-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }
        form {
            border: 1px solid black;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form id="addCourseForm" method="post" action="CoordiAddCours.php">
        <h1>ADD Courses</h1>
        <label for="major">Major:</label>
        <input type="text" id="major" name="major" required><br><br>

        <label for="week">Week:</label>
        <input type="text" id="week" name="week" required><br><br>

        <label for="date">Date:</label>
        <input type="text" id="date" name="date" required><br><br>

        <label for="time">TimeS:</label>
        <input type="text" id="time" name="timeStart" required><br><br>


        <label for="time">TimeE:</label>
        <input type="text" id="time" name="timeEnd" required><br><br>

        <button type="submit">确定</button>
    </form>
</div>
<script>
    // document.getElementById('addCourseForm').addEventListener('submit', function(event) {
    //     event.preventDefault();
    //     const major = document.getElementById('major').value;
    //     const week = document.getElementById('week').value;
    //     const date = document.getElementById('date').value;
    //     const times = document.getElementById('timeS').value;
    //     const timee = document.getElementById('timeE').value;
    //
    //     console.log('Major:', major);
    //     console.log('Week:', week);
    //     console.log('Date:', date);
    //     console.log('Time:', time);
    //
    //     // Add your logic to process the form data here.
    // });
</script>
</body>
</html>
