
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <link rel="icon" href="./img/logo/icon.png">
    <!-- <link rel="icon" href="./img/logo/icon_rect.png"> -->
    <link rel="stylesheet" type="text/css" href="./css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="./css/page.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Q-work</title>
</head>
<body>
    <?php
        session_start();
        $id = $_SESSION['id'];
        $hash = $_SESSION['hash'];

        $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
        $api = "/php_apis/getTasks.php";  // APIの指定
        $url = $workspace.$api;

        // JSONにするオブジェクトの構成例
        // テスト用
        $data = array(
            /*
            "id" => "kami9811",
            "hash" => "4f35de9609f92921d0a6d758d561b9120aa1eec79a2d6e20c21ae20c17ed9dde"
            */
            "id" => $id,
            "hash" => $hash
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
        global $json_arr;
        $json_arr = json_decode($contents, true);
        //print_r($json_arr);

        //配列の最初を取り出す
        function get_first_element($arr) {
            return current($arr);
        }
        //日付の分割
        function deadline_conv($task_deadline) {
            global $year;
            global $month;
            global $day;
            global $hour;
            global $min;
            global $sec;
            $year = substr($task_deadline, 0, 4);
            $month = substr($task_deadline, 4, 2);
            $day = substr($task_deadline, 6, 2);
            $hour = substr($task_deadline, 8, 2);
            $min = substr($task_deadline, 10, 2);
            $sec = substr($task_deadline, 12, 2);
        }

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

        //優先度でのソート
        //現在は残り時間のみでのソート。
        global $new_json_arr;
        $new_json_arr = array_sort($json_arr["task_list"], "task_deadline", SORT_ASC);
        //最優先のタスクを取り出す
        global $first;
        $first = get_first_element($new_json_arr);
        // -------------------川上
        // $alert = "<script type='text/javascript'>alert('"
        //          .$first["task_id"]."');</script>";
        // echo $alert; // 最初のタスクが表示される！
        $_SESSION["first_id"] = $first["task_id"];
        // -------------------
    ?>
    <?php
    // 初期化
    if(strcmp($_SESSION["task_name"], $_POST["task_name"]) == 0){
        $_POST["task_name"] = NULL;
        $_POST["trigger"] = NULL;
        $_POST["task-time"] = NULL;
    }
    if(isset($_POST["task_name"])){
        // input 確認
        // echo $_POST["task_name"]."<br>";  // task
        // echo $_POST["trigger"]."<br>";  // 4
        // echo $_POST["task-time"]."<br>";  // 0020-02-03T04:01
        $deadline = substr($_POST["task-time"], 0, 4).substr($_POST["task-time"], 5, 2)
                    .substr($_POST["task-time"], 8, 2).substr($_POST["task-time"], 11, 2)
                    .substr($_POST["task-time"], 14, 2)."00";  // 00200203040100

        //データベースに送信
        // API URL設定
        $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
        $api = "/php_apis/resistTask.php";  // APIの指定
        $url = $workspace.$api;
        // JSONにするオブジェクトの構成例
        $data = array(
            "id" => $id,
            "hash" => $hash,
            "task" => $_POST["task_name"],
            "task_deadline" => $deadline,
            "task_weight" => $_POST["trigger"],
            "task_wants" => 0,
            "task_will" => "000000"
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
        $context = stream_context_create($options);
        $contents = file_get_contents($url, false, $context);
        $contents = json_decode($contents, true);
        // echo $contents["message"];
        header("Location: https://kn46itblog.com/hackathon/TechStudyGroup/page.php");
        // セッション登録
        $_SESSION["task_name"] = $_POST["task_name"];
        exit();
    }
    ?>
    <section class="page">
        <section class="header">
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button href="./logout.php" class="btn" name="logout">Logout (<?php echo $id; ?>さん)</button>
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
                <div id="open" class="create"><i class="fas fa-plus"></i> 新規</div>
                <div id="mask" class="hidden"></div>
                <section id="modal" class="hidden">
                    <form action="" method="post">
                        <p>タスクについて記述後、「作成」ボタンを入力してください。</p>
                        <div id="task" class="task"> <!--この関数、クラスはまだ、cssでは設定していません -->
                            <p>タスク名<br>
                                <input type="text" name="task_name">
                            </p>

                            <p>重要度<br>
                                低い
                                <input type="radio" name="trigger" value="1">1
                                <input type="radio" name="trigger" value="2">2
                                <input type="radio" name="trigger" value="3">3
                                <input type="radio" name="trigger" value="4">4
                                <input type="radio" name="trigger" value="5">5
                            　　高い</p>

                            <p>締め切り<br>
                                <input type="datetime-local" name="task-time" class="bts">
                            </p>
                        </div>
                        <div id="close"><button type="submit" class="create"><i class="fas fa-plus"></i> 作成</button></div>
                    </form>
                </section>
                <div class="task">
                    <ul>
                        <li class="task-name">作業名:&emsp;<?php
                        //$first = get_first_element($new_json_arr);
                        print_r($first["task"]);
                        ?></li>
                        <hr>
                        <li><form action="#" name="form1">
                                作業時間:&emsp;
                                <span id="timerLabel" style="width: 150px;">00:00:00</span>
                                &ensp;<input type="button" id="startBtn" value="START" onclick="start()" class="btn btn-secondary btn-sm"/>
                                &ensp;<input type="button" value="STOP" onclick="stop()" class="btn btn-secondary btn-sm"/>
                                &ensp;<input type="button" value="RESET" onclick="reset()" class="btn btn-secondary btn-sm"/>
                            </form>
                            <!--javascriptを直接書き込みました。後はボタンが押されて起動するようにするだけです。-->
                            <script type="text/javascript">
                            var status = 0; // 0:停止中 1:動作中
                            var time = 0;
                            var startBtn = document.getElementById("startBtn");
                            var timerLabel = document.getElementById('timerLabel');
                            // STARTボタン
                        	function start(){
                                // 動作中にする
                                status = 1;
                                // スタートボタンを押せないようにする
                                startBtn.disabled = true;

                                timer();
                            }
                            // STOPボタン
                            function stop(){
                                // 停止中にする
                                status = 0;
                                // スタートボタンを押せるようにする
                                startBtn.disabled = false;
                            }
                            // RESETボタン
                            function reset(){
                                // 停止中にする
                                status = 0;
                                // タイムを0に戻す
                                time = 0;
                                // タイマーラベルをリセット
                                timerLabel.innerHTML = '00:00:00';
                                // スタートボタンを押せるようにする
                                startBtn.disabled = false;
                            }

                            function timer(){
                                // ステータスが動作中の場合のみ実行
                                if (status == 1) {
                                    setTimeout(function() {
                                        time++;

                                        // 分・秒・ミリ秒を計算
                                        var hour = Math.floor(time/100/60/60);
                                        var min = Math.floor(time/100/60);
                                        var sec = Math.floor(time/100);

                                        // 時が１桁の場合は、先頭に０をつける
                                        if (hour < 10) hour = "0" + hour;

                                        // 分が１桁の場合は、先頭に０をつける
                                        if (min < 10) min = "0" + min;

                                        // 秒が６０秒以上の場合　例）89秒→29秒にする
                                        if (sec >= 60) sec = sec % 60;

                                        // 秒が１桁の場合は、先頭に０をつける
                                        if (sec < 10) sec = "0" + sec;

                                        // タイマーラベルを更新
                                        timerLabel.innerHTML = hour + ":" + min + ":" + sec;

                                        // 再びtimer()を呼び出す
                                        timer();
                                    }, 10);
                                }
                            }
                            </script>
                        </li>
                        <li class="deadline">締め切り: &emsp;<?php
                            deadline_conv($first["task_deadline"]);
                            echo "{$year}年{$month}月{$day}日{$hour}時{$min}分";
                        ?></li>
                        <hr>
                        <li>
                            <p>ワークフロー</p>
                            <ul class="input-group mb-3">
                                <!--javascriptを直接書き込みました。ワークフローの追加処理-->
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                <script type="text/javascript">
                                    $(document).on("click", ".add", function() {
                                                $(this).parent().clone(true).insertAfter($(this).parent());
                                    });
                                    $(document).on("click", ".del", function() {
                                            var target = $(this).parent();
                                            if (target.parent().children().length > 1) {
                                                target.remove();
                                            }
                                    });
                                </script>
                                <div id="input_pluralBox">
                                    <div id="input_plural">
                                        <input id="add-work" type="button" value="＋" class="work-add btn add pluralBtn">
                                        <input id="add-work" type="button" value="－" class="btn del pluralBtn">
                                        <li class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label="Checkbox for following text input">
                                        </div>
                                        <input type="text" class="form-control" aria-label="Text input with checkbox">
                                        </li>
                                    </div>
                                </div>
                                <!--ここまでが追加の内容になります。-->
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="task-mng">
                    <button id="add-work" class="work-add btn">ワークフローの追加</button>
                    <div class="task-button">
                        <button class="btn btn-outline-info">ワークフローを保存</button>
                        <form action="finish.php" method="post">
                            <button class="btn finished-task">タスクの完了</button>
                        </form>
                        <form action="remove.php" method="post">
                            <button class="btn btn-outline-danger">タスクの削除</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="multi-page" class="multi no-display">
                <!-- ========================================
                    <p>ここに、マルチタスクの画面を記述します</p>
                    =========================================
                -->
                <div id="open2" class="create"><i class="fas fa-plus"></i> 新規</div>
                <div id="mask2" class="hidden2"></div>
                <section id="modal2" class="hidden2">
                    <form action="" method="post">
                        <p>タスクについて記述後、「作成」ボタンを入力してください。</p>
                        <div id="task" class="task"> <!--この関数、クラスはまだ、cssでは設定していません -->
                            <p>タスク名<br>
                                <input type="text" name="task_name">
                            </p>

                            <p>重要度<br>
                                低い
                                <input type="radio" name="trigger" value="1">1
                                <input type="radio" name="trigger" value="2">2
                                <input type="radio" name="trigger" value="3">3
                                <input type="radio" name="trigger" value="4">4
                                <input type="radio" name="trigger" value="5">5
                            　　高い</p>

                            <p>締め切り<br>
                                <input type="datetime-local" name="task-time" class="bts">
                            </p>
                        </div>
                        <div id="close2"><button type="submit" class="create"><i class="fas fa-plus"></i> 作成</button></div>
                    </form>
                </section>
                <?php
                //セッションデータを初期化
                //セッションIDの新規発行、又は、既存のセッションを読み込む
                //$_SESSIONを読み込む
                //session_start();
                // API URL設定
                // API URL設定
                /*
                $workspace = "https://kn46itblog.com/hackathon/TechStudyGroup";
                $api = "/php_apis/getTasks.php";  // APIの指定
                $url = $workspace.$api;

                // JSONにするオブジェクトの構成例
                // テスト用
                $data = array(

                    "id" => "kami9811",
                    "hash" => "4f35de9609f92921d0a6d758d561b9120aa1eec79a2d6e20c21ae20c17ed9dde"

                    "id" => $id,
                    "hash" => $hash
                );
                */
                /*
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
                /*
                // ストリームコンテキストの生成
                // ストリーム
                // -> I/Oデータをプログラムで扱えるよう抽象化したもの
                //    -> 抽象化の過程でストリームラッパーが用いられる
                $context = stream_context_create($options);
                // POST送信
                $contents = file_get_contents($url, false, $context);
                // APIのレスポンスをArrayに変換
                $json_arr = json_decode($contents, true);
                */
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
                /*
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
                */

                //タスクの表示
                function draw_task($new_json_arr) {
                    //優先度でのソート
                    //現在は残り時間のみでのソート。
                    //$new_json_arr = array_sort($json_arr["task_list"], "task_deadline", SORT_ASC);
                    $i = 0;
                    echo '<div class="circle_pare">';
                    foreach ($new_json_arr as $task_arr) {
                        //print_r($task_arr["task_deadline"]);
                        //deadline_conv($task_arr["task_deadline"]);
                        print_r($year);
                        //echo "{$year}年{$month}月{$day}日{$hour}時{$min}分";
                        echo '<div class="circle pri_'.$i.'"> '.$task_arr["task"].'<p></p>
                        '.substr($task_arr["task_deadline"], 0, 4).'年'.substr($task_arr["task_deadline"], 4, 2).'月'.substr($task_arr["task_deadline"], 6, 2).'日'.substr($task_arr["task_deadline"], 8, 2).'時'.substr($task_arr["task_deadline"], 10, 2).'分
                        </div>';
                        $i++;
                    }
                    echo '</div>';
                }

                //print_r($json_arr);
                if($json_arr["total_tasks"] != 0) {
                    draw_task($new_json_arr);
                }else {
                    echo '<div class="notask"><p> タスクデータがありません... </p><img class="notask_img" src="./img/notask.png" style="width:80%;"></div>';

                }
                ?>

            </div>
        </section>
    </section>
    <script src="https://kit.fontawesome.com/402de0a268.js" crossorigin="anonymous"></script>
    <script src="./js/function.js" type="text/javascript"></script>
    <script src="./js/window_test.js" type="text/javascript"></script>
</body>
</html>
