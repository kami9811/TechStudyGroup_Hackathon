<?php
// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONエンコードされた文字列をArrayに
$contents = json_decode($json_string, true);

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "key1" => $contents["key1"],
  "key2" => $contents["key2"],
  "box1" => array(
    "key1-1" => $contents["box1"]["key1-1"]
  )
);
// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print ($json);
?>
