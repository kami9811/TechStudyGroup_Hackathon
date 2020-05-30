<?php
// API URL設定
$workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
$api = "/php_apis/finishTask.php";  // APIの指定
// $api = "/php_apis/testPost.php";  // APIの指定
$url = $workspace.$api;

// JSONにするオブジェクトの構成例
$data = array(
	"id" => "kami9811",
	"hash" => "4f35de9609f92921d0a6d758d561b9120aa1eec79a2d6e20c21ae20c17ed9dde",
	"task_id" => 9
);

// JSON形式に変換
$data = json_encode($data);

// ストリームコンテキストのオプションを作成
$options = array(
// HTTPコンテキストオプションをセット
  'http' => array(
    'method'=> 'POST',
    'header'=> 'Content-type: application/json; charset=UTF-8', //JSON形式で表示
    'content' => $data
  )
);

// ストリームコンテキストの生成
// ストリーム
// -> I/Oデータをプログラムで扱えるよう抽象化したもの
//    -> 抽象化の過程でストリームラッパーが用いられる
$context = stream_context_create($options);
// POST送信
$contents = file_get_contents($url, false, $context);

echo $contents."<br><br>";

// APIのレスポンスをArrayに変換
$contents = json_decode($contents, true);
print_r($contents);


// keyの存在確認
/*
if (array_key_exists("user_email", $contents)){
	echo "<br>EXISTS<br>";
}
else{
	echo "<br>NOT EXISTS<br>";
}
*/

// echo "<br><br>".$contents["task_list"]["task4"]["task"];

/*
  $contents = array(
    "prm1" => 1,
    "prm2" => "value2",
    "arr1" => array(
      "prm1" => "parm_arr1-1"
    )
  );
  が返ってきたとすると:
    $contents["prm1"], $contents["arr1"]["prm1"]のようにして指定可能
*/
/*
$data = array(
	"status" => 200,
	"message" => "タスクの取得に成功しました.",
	"total_tasks" => 4,
	"task_list" => array(
		"task1" => array(
			"task_id" => 1,
			"task" => "論文執筆",
			"task_deadline" => "20200920235959",
			"task_weight" => 10,
			"task_wants" => 6,
		    "task_will" => "200000",
			"task_time" => "010000"
		),
		"task2" => array(
	    "task_id" => 2,
			"task" => "言語処理課題",
			"task_deadline" => "20200619102000",
			"task_weight" => 5,
			"task_wants" => 8,
		    "task_will" => "004000",
			"task_time" => "000000"
		),
    "task3" => array(
	    "task_id" => 4,
			"task" => "進路希望調査",
			"task_deadline" => "20200620090000",
			"task_weight" => 2,
			"task_wants" => 5,
		  "task_will" => "001000",
			"task_time" => "000000"
		),
    "task4" => array(
	    "task_id" => 6,
			"task" => "参考書読み切り目標",
			"task_deadline" => "20200630235959",
			"task_weight" => 7,
			"task_wants" => 9,
		  "task_will" => "080000",
			"task_time" => "013000"
		)
	)
);
*/
?>
