<?php
/*
{
  "id": "ID",
  "hash": "HASH",
	"done_task_id": DONE_TASK_ID
}
*/
// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONエンコードされた文字列をArrayに
$contents = json_decode($json_string, true);

$link = new mysqli("mysql8004.xserver.jp", "kn46itblog_wp1",
                   "bf672rr3d6", "kn46itblog_tsghack");
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

// TASK_IDを取り出して, 次のTASK_IDを同定
$sql = "SELECT task_id FROM task_information WHERE id='"
       .$contents["id"]."'";
$res = $link -> query($sql);
$exits_tasks = array();
while($data = $res -> fetch_assoc()){
  array_push($exits_tasks, $data["task_id"]);
}
$max_task_id = max($exits_tasks);
$task_id = $max_task_id + 1;

// DONE_TASK_IDを取り出す
$sql = "SELECT * FROM done_tasks WHERE id='"
       .$contents["id"]."' AND done_task_id=".$contents["done_task_id"];
$res = $link -> query($sql);
$data = $res -> fetch_assoc();
// DONE_TASK の登録
$sql = "INSERT INTO task_information (resistration_number, id, task_id, task,
        task_deadline, task_weight, task_wants, task_will, task_time)
        VALUES (NULL, '"
       .$data["id"]."', "
       .$task_id.", '"
       .$data["task"]."', '"
       .$data["task_deadline"]."', "
       .$data["task_weight"].", "
       .$data["task_wants"].", '"
       .$data["task_will"]."', '"
       .$data["done_task_time"]."')";
// echo $sql;
$link -> query($sql);

// 完了タスクを消去
$sql = "DELETE FROM done_tasks WHERE done_task_id=".$contents["done_task_id"]
       ." AND id='".$contents["id"]."'";
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
	"message" => "タスク完了の取り消しに成功しました."
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
