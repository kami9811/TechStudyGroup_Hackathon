<?php
session_start();
$_SESSION = array();

if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}

session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="./img/logo/icon.png">
    <link rel="stylesheet" type="text/css" href="./css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="./css/login.css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Thank you | Q-work</title>
</head>
<body>
    <section class="page">
        <div class="logo"><img src="./img/logo/logo.png" alt="logo" /></div>
        <section id="logout-form">
            <form action="" method="POST">
                <h2>ログアウトしました。</h2>
                <h4> Thank you.</h>
                <button type="submit" class="btn"></button>
            </form>
        </section>
        <section id="register">
            <a href="./login.php"><button type="submit" name="regist-usr" class="btn">ログイン画面へ</button></a>
        </section>
    </section>
</body>
</html>