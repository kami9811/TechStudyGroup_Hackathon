<?php
    session_start();
    $alert = "<script type='text/javascript'>alert('"
             .$_SESSION["first_id"]."');</script>";
    echo $alert; // 最初のタスクが表示される！
    // API URL設定
    $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
    $api = "/php_apis/finishTask.php";  // APIの指定
    $url = $workspace.$api;

    // JSONにするオブジェクトの構成例
    $data = array(
        "id" => $_SESSION["id"],
      	"hash" => $_SESSION["hash"],
      	"task_id" => $_SESSION["first_id"],
      	"task_time" => "000000"
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

    // APIのレスポンスをArrayに変換
    $contents = json_decode($contents, true);

    header("Location: https://kn46itblog.com/hackathon/TechStudyGroup/page.php");
    exit();
?>
