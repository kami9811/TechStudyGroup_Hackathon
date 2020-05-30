<?php
// API URL設定
$workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
$api = "/php_apis/***.php";  // APIの指定
$url = $workspace.$api;

// JSONにするオブジェクトの構成例
$data = array(
  "prm1" => 1,
  "prm2" => "value2",
  "arr1" => array(
    "prm1" => "parm_arr1-1"
  )
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
/*
  $contents = array(
    "prm1" => 1,
    "prm2" => "value2",
    "arr1" => array(
      "prm1" => "parm_arr1-1"
    )
  );
  が返ってきたとすると:
    $contents["prm1"], $contents["arr1"]["prm1"]のようにして指定可能
?>
