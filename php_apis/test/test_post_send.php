<?php
// API URL設定
$workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
$api = "/php_apis/test/test_post_receive.php";
$url = $workspace.$api;

echo "API: ".$url."<br>";

// JSONにするオブジェクトの構成例
$data = array(
  "key1" => 1,
  "key2" => "value2",
  "box1" => array(
    "key1-1" => "value1-1"
  )
);
echo "Before Encoded Sent JSON: ".$data."<br>";

// JSON形式に変換
$data = json_encode($data);
echo "Encoded Sent JSON: ".$data."<br>";

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

// reception.php のレスポンスを表示
echo "PHP変数:";
echo $contents."<br>";
$contents = json_decode($contents, true);
echo $contents["key1"]."<br>";
echo $contents["box1"]["key1-1"];
?>
