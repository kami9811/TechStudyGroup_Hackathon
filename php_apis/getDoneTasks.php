<?php
/*
{
	"id": "ID",
	"hash": "HASH"
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

// task情報を取り出す
$sql = "SELECT * FROM done_tasks WHERE id='".$contents["id"]."'";
$res = $link -> query($sql);
$results = array(
  "status" => 200,
  "message" => "完了したタスクの取得に成功しました.",
  "total_done_tasks" => 0,
  "done_task_list" => array()
);
// $task_num = 1;
$task_num = 0;
// $results["task_list"] = array_merge($results["task_list"],
//                                     array("task".$task_num => array()));
$task_info_array = array();
while($data = $res -> fetch_assoc()){
  $task_num = $task_num + 1;
  $results["done_task_list"] = array_merge($results["done_task_list"],
                                           array("done_task".$task_num => array()));
  $task_info_array = array_merge($task_info_array,
                                 array(
                                   "done_task_id" => $data["done_task_id"],
                                   "task" => $data["task"],
                                   "task_deadline" => $data["task_deadline"],
                                   "task_weight" => $data["task_weight"],
                                   "task_wants" => $data["task_wants"],
                                   "task_will" => $data["task_will"],
                                   "done_task_time" => $data["done_task_time"]
                                 ));
  $results["done_task_list"]["done_task".$task_num] = array_merge($results["done_task_list"]["done_task".$task_num],
                                                                  $task_info_array);
}
$results["total_done_tasks"] = $task_num;

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
