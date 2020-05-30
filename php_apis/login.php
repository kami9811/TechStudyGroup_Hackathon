<?php
/*
// 入力JSON
{
	"id": "ID",
	"password": "PASSWORD"
}
*/
// 時刻の取得
$now = new DateTime();
$login_time = $now->format('YmdHis');
// var_dump($str);  // string(14) "20200530232319"

// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONエンコードされた文字列をArrayに
$contents = json_decode($json_string, true);

$link = new mysqli("mysqlhost", "user",
                   "password", "database");  // Needed to fix in your environment.
$link -> set_charset("utf8");

// DBにからパスハッシュの取り出し
$sql = "SELECT password FROM user_verification WHERE id='".$contents["id"]."'";
$res = $link -> query($sql);
$resisPass = $res -> fetch_assoc();
// print_r($data);  // Array ( [password] => a14bCjWKst/zM )
// DBにからパスハッシュの取り出し
$sql = "SELECT sign_time FROM user_signup WHERE id='".$contents["id"]."'";
$res = $link -> query($sql);
$sign_time = $res -> fetch_assoc();
// print_r($sign_time);  // Array ( [sign_time] => 20200531000314 )
// Checking password
$password = $contents["password"];
$p_hash = hash('sha256', $password);
$s_hash = hash('sha256', $sign_time["sign_time"]);
$checkPass = crypt($p_hash, $s_hash);
if (strcmp($resisPass["password"], $checkPass) != 0){
  $results = array(
    "status" => 406,
    "message" => "ユーザID または パスワードが間違っています."
  );
  header("Content-type: application/json; charset=UTF-8");
  $json = json_encode($results);
  print($json);

  $link -> close;
  exit();
}

// UPDATE login_time
$sql = "UPDATE user_verification SET login_time='".$login_time
       ."' WHERE id='".$contents["id"]."'";
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// getUserHash
$user_hash = hash('sha256', $login_time);

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
	"message" => "ログインに成功しました.",
	"hash" => $user_hash
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);

?>
