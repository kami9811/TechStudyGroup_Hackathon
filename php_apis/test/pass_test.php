<?php
$password = "yuta1108";
// $password = "yuta";
$sign_time = "20200530211214";

$p_hash = hash('sha256', $password);
$s_hash = hash('sha256', $sign_time);

echo $p_hash."<br>".$s_hash."<br>";

$checkPass = crypt($p_hash, $s_hash);
$resisPass = "bfU.MQullmfX6";  // hash in password "yuta1108"
if (strcmp($checkPass, $resisPass) == 0){
  echo "Verification has completed!<br>";
}
else{
  echo "Verification has failured...<br>";
}
echo $checkPass;
?>
