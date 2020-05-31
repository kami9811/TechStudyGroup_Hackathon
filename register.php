<?php
if(isset($_POST['id']) && isset($_POST['password']) && isset($_POST['email'])) {
    // API URL設定
    $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
    $api = "/php_apis/signup.php";  // APIの指定
    // $api = "/php_apis/testPost.php";  // APIの指定
    $url = $workspace.$api;
    // JSONにするオブジェクトの構成例
    $data = array(
      "email" => htmlspecialchars($_POST['email']),
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
    $array = json_decode( $contents , true ) ;
    echo $contents;
    header("Location:./login.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="./img/logo/icon.png">
    <link rel="stylesheet" type="text/css" href="./css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="./css/register.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Register | Q-work</title>
</head>
<body>
    <section class="page">
        <div class="logo">
            <a href="./login.php"><img src="./img/logo/logo.png" alt="logo" /></a>
            <h1>新規登録</h1>
        </div>
        <section id="register-form">
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="id" placeholder="登録名" required /><br>
                    <input type="email" class="form-control" name="email" placeholder="メールアドレス" required /><br>
                    <input type="password" class="form-control" name="password" id="password" placeholder="パスワード" required /><br>
                    <input type="password" class="form-control" name="password2" placeholder="確認用パスワード" oninput="CheckPassword(this)" required/><br>
                    <script>
                        function CheckPassword(password2) {
                            var pass1 = password.value;
                            var pass2 = password2.value;
                            if( pass1 != pass2 ) {
                                password2.setCustomValidity("入力値が一致しません");
                            }else{
                                password2.setCustomValidity("");
                            }
                        }
                    </script>
                    <button type="submit" class="btn">登録する</button>
                </div>
            </form>
        </section>
        <section id="register">
            <a href="./login.php"><button type="submit" name="regist-usr" class="btn btn-outline-secondary">ログイン画面に戻る</button></a>
        </section>
    </section>
</body>
</html>
