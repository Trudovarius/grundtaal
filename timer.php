<?php
if(!isset($_SESSION)){session_start();}
$db_connect = mysqli_connect ('localhost', 'root', '', 'rp_web', '3306');
$id = $_SESSION['id'];
date_default_timezone_set('Europe/Bratislava');
$cities = mysqli_query($db_connect, "SELECT * FROM city WHERE id = '$id' ");
$timer = mysqli_fetch_array($cities);
$now = date('Y-m-d H:i:s');
$finish = $timer['buildFinish'];
$finished = strtotime($finish) - strtotime($now);
$time = gmdate('H:i:s', $finished);
echo "{$timer['typeOfBuild']} will be finished in <span id='time'>{$time}</span>";