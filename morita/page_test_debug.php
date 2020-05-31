<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <link rel="icon" href="./img/logo/icon.png">
    <link rel="stylesheet" type="text/css" href="./css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="./css/page.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Q-work</title>
</head>
<body>
    <section class="page">
        <section class="header">
            <div class="logout">
                <form action="logout.php" method="GET">
                    <button class="btn" name="logout">Logout</button>
                </form>
            </div>
            <div class="logo"><img src="./img/logo/logo_full.png" alt="logo" /></div>
        </section>
        <section class="main">
            <div class="select-btn">
                <div id="single-btn" class="current single-btn">シングルタスク</div>
                <div id="multi-btn" class="multi-btn">マルチタスク</div>
            </div>
            <div id="single-page" class="single">
                <div class="create"><i class="fas fa-plus"></i> 作成</div>
                <div class="task">
                    <h1 class="task-name">作業名： </h1>
                    <h2 class="timer">作業時間： </h2>
                    <h2 class="work-flow"></h2>
                    <button id="add-work" class="btn btn-primary add-work">ワークフローの追加</button>
                </div>
            </div>
            <div id="multi-page" class="multi no-display">
                <!-- ========================================
                    <p>ここに、マルチタスクの画面を記述します</p>
                    =========================================
                -->

                <?php
                // API URL設定
                // API URL設定
                $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
                $api = "/php_apis/getTasks.php";  // APIの指定
                $url = $workspace.$api;

                // JSONにするオブジェクトの構成例
                // テスト用
                $data = array(
                    "id" => "kami9811",
                    "hash" => "4f35de9609f92921d0a6d758d561b9120aa1eec79a2d6e20c21ae20c17ed9dde"
                );

                // print_r($data);  // 取得はされてるっぽい
                // JSON形式に変換
                $data = json_encode($data);
                // ストリームコンテキストのオプションを作成
                $options = array(
                    // HTTPコンテキストオプションをセット
                    'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/json; charset=UTF-8', //JSON形式で表示
                        'content' => $data
                    )
                );
                // ストリームコンテキストのオプションを作成
                $options = array(
                    // HTTPコンテキストオプションをセット
                    'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/json; charset=UTF-8', //JSON形式で表示
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
                // APIのレスポンスをArrayに変換
                $json_arr = json_decode($contents, true);
                //仮データを使います。
                //apiに変更
                /*
                $json_arr = array(
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
                /*
                function deadline_conv {
                    $yy = substr($task_deadline, 0, 4);
                    $mm
                }
                */
                //ソート
                function array_sort($array, $on, $order=SORT_ASC) {
                    $new_array = array();
                    $sortable_array = array();

                    if (count($array) > 0) {
                        foreach ($array as $k => $v) {
                            if (is_array($v)) {
                                foreach ($v as $k2 => $v2) {
                                    if ($k2 == $on) {
                                        $sortable_array[$k] = $v2;
                                    }
                                }
                            } else {
                                $sortable_array[$k] = $v;
                            }
                        }

                        switch ($order) {
                            case SORT_ASC:
                                asort($sortable_array);
                            break;
                            case SORT_DESC:
                                arsort($sortable_array);
                            break;
                        }

                        foreach ($sortable_array as $k => $v) {
                            $new_array[$k] = $array[$k];
                        }
                    }

                    return $new_array;
                }

                //タスクの表示
                function draw_task($json_arr) {
                    $new_json_arr = array_sort($json_arr["task_list"], "task_deadline", SORT_ASC);  //締め切り時間でのソート
                    $i = 0;
                    foreach ($new_json_arr as $task_arr) {
                        echo '<div class="circle pri_'.$i.'"> '.$task_arr["task"].'

                        </div>';
                        $i++;
                    }
                }


                draw_task($json_arr);
                ?>


            </div>
        </section>
    </section>
    <script src="https://kit.fontawesome.com/402de0a268.js" crossorigin="anonymous"></script>
    <script src="./js/function.js" type="text/javascript"></script>
</body>
</html>
