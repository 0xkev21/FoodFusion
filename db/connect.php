<?php

$host = "localhost";
$port = 3306;
$user = "root";
$pass = "Abc@1234";
$dbname = "foodfusion";

$con = new mysqli($host, $user, $pass, $dbname, $port);
if ($con->errno) {
  echo "Connection Failed";
}
