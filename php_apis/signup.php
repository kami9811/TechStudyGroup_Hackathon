<?php
// 時刻の取得
$now = new DateTime();
$sign_time = $now->format('YmdHis');
// var_dump($str);  // string(14) "20200530232319"

// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONエンコードされた文字列をArrayに
$contents = json_decode($json_string, true);

$link = new mysqli("mysqlhost", "user",
                   "password", "database");  // Needed to fix in your environment.
$link -> set_charset("utf8");

// DBに登録
// user_signup
$sql = "INSERT INTO user_signup (user_number, user_email, id, sign_time, sign) "
       ."VALUES (NULL, '".$contents["email"]."', '".$contents["id"]."', '"
       .$sign_time."', 0)";
// echo $sql;
$link -> query($sql)."<br>";
// user_verification
// making hash
$password = $contents["password"];
$p_hash = hash('sha256', $password);
$s_hash = hash('sha256', $sign_time);
$checkPass = crypt($p_hash, $s_hash);

$sql = "INSERT INTO user_verification (user_number, id, password, login_time) "
       ."VALUES (NULL, '".$contents["id"]."', '".$checkPass."', NULL)";
// echo $sql;
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
  "message" => "新規登録完了しました.",
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);

?>
