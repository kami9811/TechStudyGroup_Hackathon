<?php
$link = new mysqli("mysql8004.xserver.jp", "kn46itblog_wp1",
                   "bf672rr3d6", "kn46itblog_tsghack");
$link -> set_charset("utf8");

// DBにからログイン時間の取り出し
$sql = "SELECT login_time FROM user_verification WHERE id='".$_GET["id"]."'";
$res = $link -> query($sql);
$login_time = $res -> fetch_assoc();
echo "LAST_LOGIN: ";
var_dump($login_time["login_time"]);
echo "<br>USER_HASH: ";
// MySQLサーバの接続を切断
$link -> close;

// Making $user_hash
$user_hash = hash('sha256', $login_time["login_time"]);
var_dump($user_hash);
?>
