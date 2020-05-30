<?php
/*
{
  "id": "ID",
  "hash": "HASH",
  "task_id": TASK_ID,
  "task_time": "SPEND_TIME"
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

// DONE_TASK_IDを取り出して, 次のDONE_TASK_IDを同定
$sql = "SELECT done_task_id FROM done_tasks WHERE id='"
       .$contents["id"]."'";
$res = $link -> query($sql);
$exits_tasks = array();
while($data = $res -> fetch_assoc()){
  array_push($exits_tasks, $data["done_task_id"]);
}
// print_r($exits_tasks);  // Array ( [0] => 1 [1] => 3 [2] => 6 )
$max_id = max($exits_tasks);
$done_task_id = $max_id + 1;

// TASK情報を取り出す
$sql = "SELECT * FROM task_information WHERE id='"
       .$contents["id"]."' AND task_id=".$contents["task_id"];
$res = $link -> query($sql);
$data = $res -> fetch_assoc();
// DONE_TASK の登録
$sql = "INSERT INTO done_tasks (resistration_number, id, done_task_id, task,
        task_deadline, task_weight, task_wants, task_will, done_task_time)
        VALUES (NULL, '"
       .$data["id"]."', "
       .$done_task_id.", '"
       .$data["task"]."', '"
       .$data["task_deadline"]."', "
       .$data["task_weight"].", "
       .$data["task_wants"].", '"
       .$data["task_will"]."', '"
       .$contents["task_time"]."')";
// echo $sql;
$link -> query($sql);

// 完了したタスク番号を消去
$sql = "DELETE FROM task_information WHERE task_id=".$contents["task_id"]
       ." AND id='".$contents["id"]."'";
$link -> query($sql);

// 完了したタスク下のサブタスクを消去
$sql = "DELETE FROM subtask_information WHERE id='"
       .$contents["id"]."' AND task_id="
       .$contents["task_id"];
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
	"message" => "タスク完了の記録に成功しました."
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
