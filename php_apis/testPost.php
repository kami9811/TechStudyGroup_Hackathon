<?php
// 受信
$json_string = file_get_contents('php://input');  // raw data

// JSONをそのまま返送
print ($json_string);
?>
