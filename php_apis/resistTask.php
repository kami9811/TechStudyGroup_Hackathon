<?php
/*
{
	"id": "ID",
	"hash": "HASH",
	"task": "TASK_TITLE",
	"task_deadline": "DEADLINE",
	"task_weight": 1-10,  # 重要度
	"task_wants": 1-10,  # やりたい度
  "task_will": "TIME"  # 完了予測時間
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
// print_r($exits_tasks);  // Array ( [0] => 1 [1] => 3 [2] => 6 )
$max_task_id = max($exits_tasks);
$task_id = $max_task_id + 1;
// echo $task_id;  // 7
// print($max_task_id+1);  // 7

// Taskの登録
$sql = "INSERT INTO task_information (resistration_number, id, task_id, task,
        task_deadline, task_weight, task_will, task_time) VALUES (NULL, '"
       .$contents["id"]."', ".$task_id.", '".$contents["task"]."', '"
       .$contents["task_deadline"]."', ".$contents["task_weight"].", '"
       .$contents["task_will"]."', '000000')";
// INSERT INTO task_information
// (resistration_number, id, task_id, task, task_deadline, task_weight, task_will, task_time)
// VALUES (NULL, 'kami9811', 7, '論文執筆', '20200920235959', 9, 800000, '000000')
// print($sql);
$link -> query($sql);

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSON作成
// JSONにするArrayを作成
$results = array(
  "status" => 200,
	"message" => "タスク登録に成功しました.",
	"task_id" => $task_id
);

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
