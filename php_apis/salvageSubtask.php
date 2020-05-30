<?php
/*
{
  "id": "ID",
  "hash": "HASH",
  "task_id": TASK_ID,
  "subtask_id": SUBTASK_ID
}
*/
// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONエンコードされた文字列をArrayに
$contents = json_decode($json_string, true);

$link = new mysqli("mysqlhost", "user",
                   "password", "database");  // Needed to fix in your environment.
$link -> set_charset("utf8");

// DBにからログイン時間の取り出し, user_hash 生成
$sql = "SELECT login_time FROM user_verification WHERE id='"
       .$contents["id"]."'";
$res = $link -> query($sql);
$login_time = $res -> fetch_assoc();
$user_hash = hash('sha256', $login_time["login_time"]);
// user_hash の比較
if (strcmp($contents["hash"], $user_hash) != 0){
  $results = array(
    "status" => 400,
  	"message" => "リクエストが無効です."
  );
  header("Content-type: application/json; charset=UTF-8");
  $json = json_encode($results);
  print($json);

  $link -> close;
  exit();
}

// 指定タスク番号を消去
$sql = "UPDATE subtask_information SET subtask_done=0 WHERE task_id="
       .$contents["task_id"]." AND id='"
       .$contents["id"]."' AND subtask_id="
       .$contents["subtask_id"];
$link -> query($sql);
$results = array(
  "status" => 200,
  "message" => "サブタスク完了取り消しの記録に成功しました."
);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
