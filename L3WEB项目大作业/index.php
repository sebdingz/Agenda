<?php
ini_set("display_errors", 0);
/*error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);*/

session_start();
$_SESSION['major']=null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>web登录界面</title>

    <meta name="keywords" content="web登录界面"/>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="../projectfile/css/style.css" rel='stylesheet' type='text/css' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head>



<body>
<!--start-main-->
<h1>Welcome!<span>Please login...</span></h1>
<div class="login-box">
    <form action="index.php" method="post" >
        <input type="text"  name="username" class="text" value="Username" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}" >
        <input type="password"  name="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
<div class="remember">
            <a href="#"><p>Remember me</p></a>
            <h4>Forgot your password?<a href="#">Click here.</a></h4>
        </div>
        <div class="clear"> </div>

        <div class="btn">
            <input type="submit" name="login"  value="login" >
            <input type="submit" name="regist" onclick=" " value="regist">
        </div>
        <div id="message"></div>
        <div class="clear"> </div>
    </form>

</div>
<!--//End-login-form-->
<!--start-copyright-->
<div class="copy-right">

</div>
<!--//end-copyright-->

</body>
</html>



<?php
ini_set("display_errors", 0);
/*error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);*/
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 读取 JSON 文件
    $data = json_decode(file_get_contents('userInfo.json'), true);

    // 获取用户提交的用户名和密码
    $username = $_POST['username'];
    $password = $_POST['password'];

   // 判断用户是通过哪一个键来提交表单的 key=login是按下了登陆按键
    if (array_key_exists('login', $_POST)) {

        foreach ($data as &$user) {

            if ($user['username'] === $username && $user['password'] === $password) {
                // 存储用户名到 Cookie
                // setcookie('username', $username, time() + 3600); // 存储 1 小时
                $_SESSION['usertype'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['password'] = $user['password'];

                if($user['role']==="etudiant"){
                    $_SESSION['major'] = $user['major'];
                }
                
                // 设置用户状态为 "Online"
                $user['stat'] = 'Online';


                // 保存用户数据回 JSON 文件
                file_put_contents('userInfo.json', json_encode($data));

                $_SESSION["username"] =  $username;
                $_SESSION["password"] =  $password ;


                //  如果登录成功重定向到另一个页面


                if($user['role']=="etudiant"){
                    echo '
                <script>
                    alert("正确！");
                 
                    
                    window.location.href = "StudentPage.php";

                </script>';
                }
                if($user['role']=="coordinateur"){
                    echo '
                <script>
                     window.location.href = "CoordiPage.php";
                </script>';

                }
                if($user['role']=="responsable"){
                    echo '
                <script>
                     window.location.href = "ResponPage.php";
                </script>';

                }

                exit;
            }
        }
        // 显示错误消息
        echo '
                <script>alert("用户名或密码不正确！");
                     window.location.href = "index.php";
                </script>';

    }

    // 检查是否按下了 "register" 按钮
    if (array_key_exists('regist', $_POST)) {
        echo "regist";

        // 检查是否已存在相同的用户名
        foreach ($data as $user) {
            if ($user['username'] === $username) {
                // 用户名已存在，显示错误消息
                echo '<script>alert("用户名已存在！");window.location.href = "index.php";</script>';
                exit;
            }
        }

        // 创建新用户
        $new_user = array(
            'role' => 'etudiant',
            'username' => $username,
            'password' => $password,
            'stat' => 'Online'
        );

        // 将新用户添加到数据数组中
        array_push($data,$new_user);

        // 将数据数组写回到 JSON 文件中
        file_put_contents('userInfo.json', json_encode($data));

        // 注册成功，显示成功消息
        echo '<script>alert("注册成功！");window.location.href = "index.php";</script>';
    }
}
?>


