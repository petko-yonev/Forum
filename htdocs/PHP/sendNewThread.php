<?php

include_once "sql_connection.php";
session_start();
$userId = $_SESSION["userId"];

$threadName = mysqli_real_escape_string($conn, $_POST["NameNewThread"]);
$threadText = mysqli_real_escape_string($conn, $_POST["TextNewThread"]);


// Loop for inserting 1000 querys in database

//for($x=0; $x<1000; $x++){
//SQL query for inerting the thread`s data
$currentDate = date('Y.m.d H:i:s', strtotime('1 hour'));
$sql_threadInformation = "INSERT INTO `threads` (`name_thread`, `user_id`, `date`, `content_thread_games`) VALUES (?, ?, ?, ?)";

if(!mysqli_stmt_prepare($stmt, $sql_threadInformation)){
    echo "Problem inserting thread information in SQL";
} else {
    mysqli_stmt_bind_param($stmt, "siss", /*$x*/$threadName, $userId, $currentDate, $threadText);
    mysqli_stmt_execute($stmt);
}

//SQl query for selecting the last thread posted
$sql_lastThread = ("SELECT MAX(`thread_id`) FROM `threads`");
if(!mysqli_stmt_prepare($stmt, $sql_lastThread)){
    echo "Problem selecting last thread from threads with SQL";
} else {
    mysqli_stmt_execute($stmt);
    $result_lastThreadId = mysqli_stmt_get_result($stmt);
    while($row_lastThreadId = mysqli_fetch_assoc($result_lastThreadId)){
        $lastThreadId = $row_lastThreadId["MAX(`thread_id`)"];

        //Adding category: GAMES to thread
        if(isset($_POST["games"])){
            $sql_addCategory = ("INSERT INTO `threads_category` (`category_id`, threads_id) VALUES (?, ?)");
            if(!mysqli_stmt_prepare($stmt, $sql_addCategory)){
                echo "Problem inserting into threads category games with SQL";
            } else {
                $categoryId = 1;  
                mysqli_stmt_bind_param($stmt, "ii", $categoryId, $lastThreadId);
                mysqli_stmt_execute($stmt);
            } 
        }
        //Adding category: MOVIES to thread
        if(isset($_POST["movies"])){
            $sql_addCategory = ("INSERT INTO `threads_category` (`category_id`, threads_id) VALUES (?, ?)");
            if(!mysqli_stmt_prepare($stmt, $sql_addCategory)){
                echo "Problem inserting into threads category games with SQL";
            } else {
                $categoryId = 2;
                mysqli_stmt_bind_param($stmt, "ii", $categoryId, $lastThreadId);
                mysqli_stmt_execute($stmt);
            }
        }
        //Adding category: NEWS to thread
        if(isset($_POST["news"])){
            $sql_addCategory = ("INSERT INTO `threads_category` (`category_id`, threads_id) VALUES (?, ?)");
            if(!mysqli_stmt_prepare($stmt, $sql_addCategory)){
                echo "Problem inserting into threads category games with SQL";
            } else {
                $categoryId = 3;
                mysqli_stmt_bind_param($stmt, "ii", $categoryId, $lastThreadId);
                mysqli_stmt_execute($stmt);
            }
        }
        //Adding category: JOB to thread
        if(isset($_POST["job"])){
            $sql_addCategory = ("INSERT INTO `threads_category` (`category_id`, threads_id) VALUES (?, ?)");
            if(!mysqli_stmt_prepare($stmt, $sql_addCategory)){
                echo "Problem inserting into threads category games with SQL";
            } else {
                $categoryId = 4;
                mysqli_stmt_bind_param($stmt, "ii", $categoryId, $lastThreadId);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}
//}
header ("Location: threads.php?page=1&category=1");
exit();