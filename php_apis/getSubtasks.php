<?php
/*
{
  "id": "ID",
  "hash": "HASH",
  "task_ID": TASK_ID
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

// task情報を取り出す
$sql = "SELECT * FROM subtask_information WHERE id='".$contents["id"]
       ."' AND task_id=".$contents["task_id"];
$res = $link -> query($sql);
$results = array(
  "status" => 200,
  "message" => "サブタスクの取得に成功しました.",
  "total_subtasks" => 0,
  "subtask_list" => array()
);
$task_num = 0;
$task_info_array = array();
while($data = $res -> fetch_assoc()){
  $task_num = $task_num + 1;
  $results["subtask_list"] = array_merge($results["subtask_list"],
                                         array("subtask".$task_num => array()));
  $task_info_array = array_merge($task_info_array,
                                 array(
                                   "task_id" => $data["task_id"],
                                   "subtask_id" => $data["subtask_id"],
                                   "subtask" => $data["subtask"],
                                   "subtask_done" => $data["subtask_done"]
                                 ));
  $results["subtask_list"]["subtask".$task_num] = array_merge($results["subtask_list"]["subtask".$task_num],
                                                              $task_info_array);
}
// 最後に全部のタスク数を入力
$results["total_subtasks"] = $task_num;

// MySQLサーバの接続を切断
$link -> close;

// 返信用JSONに変換
header("Content-type: application/json; charset=UTF-8");
$json = json_encode($results);

// JSONをreturn
print($json);
?>
