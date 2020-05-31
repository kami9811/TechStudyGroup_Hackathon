<?php
//セッションデータを初期化
//セッションIDの新規発行、又は、既存のセッションを読み込む
//$_SESSIONを読み込む
session_start();
if(isset($_POST['id']) && isset($_POST['password'])) {
    // API URL設定
    $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
    $api = "/php_apis/login.php";  // APIの指定
    // $api = "/php_apis/testPost.php";  // APIの指定
    $url = $workspace.$api;
    // JSONにするオブジェクトの構成例
    $data = array(
      "id" => htmlspecialchars($_POST['id']),
      "password" => htmlspecialchars($_POST['password'])
    );
    // JSON形式に変換
    $data = json_encode($data);
    // ストリームコンテキストのオプションを作成
    $options = array(
    // HTTPコンテキストオプションをセット
      'http' => array(
        'method'=> 'POST',
        'header'=> 'Content-type: application/json; charset=UTF-8', //JSON形式で表示
        'content' => $data
      )
    );
    // ストリームコンテキストの生成
    // ストリーム
    // -> I/Oデータをプログラムで扱えるよう抽象化したもの
    //    -> 抽象化の過程でストリームラッパーが用いられる
    $context = stream_context_create($options);
    // POST送信
    $contents = file_get_contents($url, false, $context);
    $contents = json_decode($contents, true);
    if($contents['status'] == 200) {
        //IDとhashをsesstionに登録
        $_SESSION["id"] = $_POST['id'];
        $_SESSION["hash"] = $contents['hash'];
        //pege.phpへ
        header("Location:./page.php");
    }
    // ログイン失敗時, Alertの表示
    else{
        $fail_alert = $contents["message"];
        $alert = "<script type='text/javascript'>alert('".$fail_alert
                 ."');</script>";
        echo $alert;
    }
}
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
    <title>Login | Q-work</title>
</head>
<body>
    <section class="page">
        <div class="logo"><img src="./img/logo/logo.png" alt="logo" /></div>
        <section id="login-form">
            <form action="" method="POST">
                <div class="form-group">
                    <input type="id" class="form-control" name="id" placeholder="ユーザーID" required /><br>
                    <input type="password" class="form-control" name="password" placeholder="パスワード" required /><br>
                    <button type="submit" class="btn">ログイン</button>
                </div>
            </form>
        </section>
        <section id="register">
            <p>または</p>
            <a href="./register.php"><button type="submit" name="regist-usr" class="btn">新規登録</button></a>
        </section>
    </section>
</body>
</html>
