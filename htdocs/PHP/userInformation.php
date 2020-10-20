<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/userInformation.css">
    <link rel="stylesheet" type="text/css" href="../CSS/threads.css">
    <title>OverhoulForum</title>
</head>
<body>
    <header>
        <?php
            include_once "header.php";
        ?>
    </header>

    <?php
        include_once "sql_connection.php";
        //ID on user that you are looking 
        $idOnUserYouAreLooking = mysqli_real_escape_string($conn, $_GET['id']);

        //SQL query for data on user that you are looking
        $sql_InfoForUserYouAreLooking = ("SELECT `username`, `about_me`,`profile_picture_name` FROM `user` WHERE `user_id`=?");
        if(!mysqli_stmt_prepare($stmt, $sql_InfoForUserYouAreLooking)){
            echo "Problem selecting Info for other user from user with SQL";
        } else {
            mysqli_stmt_bind_param($stmt, "i", $idOnUserYouAreLooking);
            mysqli_stmt_execute($stmt);
            $result_InfoForUserYouAreLooking = mysqli_stmt_get_result($stmt);
            while($row_info = mysqli_fetch_assoc($result_InfoForUserYouAreLooking)){
                $infoUsernameHolder = $row_info["username"];
                $infoAboutUserHolder = $row_info["about_me"];
                $infoPicHolder = $row_info["profile_picture_name"];

                //Data on user
                echo'<div class="UserInfoContainer">
                        <img class="userPicInfo" src="../Profile_Pictures/'.$infoPicHolder.'">
                        <span>'.$infoUsernameHolder.'</span>
                    </div>

                    <textarea class="userInfo" disabled>'.$infoAboutUserHolder.'</textarea>

                    <div class="UserPostContainer">
                        Какво е поствал <strong>'.$infoUsernameHolder.':</strong>
                    </div>';
            }
        }

        //SQL query for threads posted by the user you are looking
        //$sql_takeUserThreads = "SELECT `thread_id`, `name_thread`, `date` FROM `threads` WHERE user_id = ? ORDER BY `thread_id` DESC;";
        $threadsOnPage = 10;
        $pageNum = $_GET["page"];
        $firstResultOnThisPage = ($pageNum - 1) * $threadsOnPage;
        $sql_takeUserThreads = "SELECT `thread_id`, `name_thread`, `date` FROM `threads` WHERE user_id = ? ORDER BY `thread_id` DESC LIMIT ". $firstResultOnThisPage . ',' . $threadsOnPage;
        if(!mysqli_stmt_prepare($stmt, $sql_takeUserThreads)){
            echo "Problem selecting Name thread and date from threads with SQL";
        } else {
            mysqli_stmt_bind_param($stmt, "i", $idOnUserYouAreLooking);
            mysqli_stmt_execute($stmt);
            $result_UserThreads = mysqli_stmt_get_result($stmt);
            echo '<article>  <table>';
                while($row_userThreads = mysqli_fetch_assoc($result_UserThreads)){
                    echo'<tr>
                            <td class="nameThread" > <a class="underline_remove" href="threadTemplate.php?idOnThread='.$row_userThreads["thread_id"].'"> '.$row_userThreads["name_thread"].' </a> </td>
                            <td class="dateThread" > <div> '.$row_userThreads["date"].' </div> </td>
                        </tr>';
                }
            echo '</table> </article>';
        }  
        //SQL for counting users threads
        $sql_numThreadsOnUser = ("SELECT COUNT(`thread_id`) FROM `threads` WHERE user_id = $idOnUserYouAreLooking ");

        if(!mysqli_stmt_prepare($stmt, $sql_numThreadsOnUser)){
            echo "Problem counting Threads on that user";
        } else {
            mysqli_stmt_execute($stmt);
            $numThreadsOnUser = mysqli_stmt_get_result($stmt);
            echo '<br><div class="pages">';
            while($row_numThreads = mysqli_fetch_assoc($numThreadsOnUser)){
                $numThreads = $row_numThreads["COUNT(`thread_id`)"];
                $lastPage = ceil($numThreads / $threadsOnPage);
                $firstpage = 1;

                if($pageNum != 1 ){
                    $link = "window.location.href='accountSettings.php?page=".$firstpage."&show=threads'";
                    echo '<button class="pageBtn" onclick="'.$link.';"> '.$firstpage.' </button>...';
                }
                if($pageNum > 5 ){
                    $lowerPage = $pageNum - 5;
                    $link = "window.location.href='accountSettings.php?page=".$lowerPage."&show=threads'";
                    echo ',<button class="pageBtn" onclick="'.$link.';"> -5 </button>';
                }
                if($pageNum > 2 ){
                    $lowerPage = $pageNum - 1;
                    $link = "window.location.href='accountSettings.php?page=".$lowerPage."&show=threads'";
                    echo ',<button class="pageBtn" onclick="'.$link.';"> -1 </button>';
                }
                    $link = "window.location.href='accountSettings.php?page=".$pageNum."&show=threads'";
                    echo'<input type="hidden" id="lastPage" value="'.$lastPage.'"></input>
                        <input name="pageInput" class="pageInput" onkeyup="changePageUserThreads()" value="'.$pageNum.'"></input>';
    
                if($pageNum < $lastPage - 1 ){
                    $incPage = $pageNum + 1;
                    $link = "window.location.href='accountSettings.php?page=".$incPage."&show=threads'";
                    echo ',<button class="pageBtn" onclick="'.$link.';"> +1 </button>';
                }
                if($pageNum < $lastPage - 5 ){
                    $incPage = $pageNum + 5;
                    $link = "window.location.href='accountSettings.php?page=".$incPage."&show=threads'";
                    echo ',<button class="pageBtn" onclick="'.$link.';"> +5 </button>';
                }
                if($pageNum != $lastPage && $lastPage != 0){
                    $link = "window.location.href='accountSettings.php?page=".$lastPage."&show=threads'";
                    echo '...<button class="pageBtn" onclick="'.$link.';"> '.$lastPage.' </button>';
                } 
            }
            }
            echo'</div>';
          
    ?>
</body>
</html>