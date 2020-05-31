<?php
if(strcmp($_SESSION["task_name"], $_POST["task_name"]) == 0){
  $_POST["task_name"] = NULL;
}
if(isset($_POST["task_name"])){
  $alert_message = $_POST["task_name"];
  $alert = "<script type='text/javascript'>alert('"
  .$alert_message."');</script>";
  echo $alert;
  $_SESSION["task_name"] = $_POST["task_name"];
}
?>
