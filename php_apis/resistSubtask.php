<?php
/*
{
  "id": "ID",
  "hash": "HASH",
  "task_id": TASK_ID,
  "subtask": "SUBTASK_TITLE"
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

// Subtaskの登録
$sql = "INSERT INTO subtask_information (resistration_number, id, task_id,
        subtask_id, subtask, subtask_done) VALUES (NULL, '"
       .$contents["id"]."', ".$contents["task_id"].", "
       .$contents["subtask_id"].", '"
       .$contents["subtask"]."', 0)";
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
	"message" => "サブタスク登録に成功しました."
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
