<?php

$dbServerName = "127.0.0.1";
$dbUserName = "root";
$dbPassword = "123pp321";
$main_db = "forum";

//Connection to SQL
$conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $main_db, 3306);
mysqli_set_charset($conn,"utf8");
$stmt = mysqli_stmt_init($conn);

if(!$conn){
    echo "Error with connection to main DB";
    exit();
}
