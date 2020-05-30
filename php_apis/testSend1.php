<?php
// API URL設定
$workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
$api = "/php_apis/signup.php";  // APIの指定
// $api = "/php_apis/testPost.php";  // APIの指定
$url = $workspace.$api;
// JSONにするオブジェクトの構成例
$data = array(
  "email" => "waku@gmail.com",
  "id" => "waku",
  "password" => "wakuwaku"
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
echo $contents."<br><br>";

// APIのレスポンスをArrayに変換
$contents = json_decode($contents, true);
print_r($contents);
?>
