<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/threads.css">
    <title>OverhoulForum</title>
</head>
<body>
    <header>
        <?php
            include_once "header.php";
        ?>
    </header>

    <article>
        <?php
            include_once "sql_connection.php";
            $idOnThread = mysqli_real_escape_string($conn, $_GET['idOnThread']);

            //SQL query for thread data
            $sql_threadInfo = "SELECT `name_thread`, `user_id`, `date`, `content_thread_games` FROM `threads` WHERE `thread_id`=?";
            if(!mysqli_stmt_prepare($stmt, $sql_threadInfo)){
                echo "Problem selecting Name on the thread from SQL";
            } else {
                mysqli_stmt_bind_param($stmt, "i", $idOnThread);
                mysqli_stmt_execute($stmt);
                $result_threadInfo = mysqli_stmt_get_result($stmt);
                while($row_threadInfo = mysqli_fetch_assoc($result_threadInfo)){

                    //Name on thread
                    echo "<div class='nameOnThread'>" .$row_threadInfo["name_thread"] ."</div>";
                    echo "<br>Автор:";

                    //Id on user posted the tread
                    $userId = $row_threadInfo["user_id"];

                    //SQL query for user`s data posted the thread
                    $sql_profilePicAndUsername = "SELECT `profile_picture_name`, `username` FROM `user` WHERE `user_id`= ?";
                    if(!mysqli_stmt_prepare($stmt, $sql_profilePicAndUsername)){
                        echo "Problem selecting Profile Picture and username from user in SQL";
                    } else {
                        mysqli_stmt_bind_param($stmt, "i", $userId);
                        mysqli_stmt_execute($stmt);
                        $result_profilePicAndUsername = mysqli_stmt_get_result($stmt);
                        while($row_picAndUser = mysqli_fetch_assoc($result_profilePicAndUsername)){

                            $dateHolder = date_create($row_threadInfo["date"]);
                            $dateFormat = date_format($dateHolder, 'd-m-Y в H:i:s');
                            $picHolder = $row_picAndUser["profile_picture_name"];
                            $usernameHolder = $row_picAndUser["username"];

                            //User posted the thread 
                            echo '<div class = "thread_poster_info">
                                    <img id="output" class="thread_user_pic" src="../Profile_Pictures/'.$picHolder.'">
                                    <a class="underline_remove" href="userInformation.php?page=1&id='.$userId.'">'.$usernameHolder.'</a>
                                    <div class="comment_date">Постнал на: '
                                        .$dateFormat.
                                    '</div>
                                 </div>';
                                
                            //Threads content
                            echo '<div class="thread_content">';
                            echo preg_replace('/\v+|\\\r\\\n/Ui','<br/>',$row_threadInfo["content_thread_games"]);
                            echo "</div>";
                        }
                    }
                }
            }
        ?>
    </article>

    <?php
        //Add new comment if you are logged in
        if($_SESSION){
            echo '<form  method="POST" action="sendNewComment.php?idOnThread='.$idOnThread.'">
                    <textarea name="TextNewComment" id="TextComment" placeholder="Напиши коментар" ></textarea>
                    <input id="SendNewComment" type="submit" value="Изпрати">
                 </form>';
         } else {
            echo '<div class="add_comment_place" >Логнете се за да коментирате</div>';
         }
        

        //Showing all comments on this thread
        $sql_loadAllComents = ("SELECT `user_id`, `date`, `content` FROM `comments` WHERE thread_id = ? ORDER BY `comment_id` DESC");
        if(!mysqli_stmt_prepare($stmt, $sql_loadAllComents)){
            echo "Problem selecting comments with SQL";
        } else {
            mysqli_stmt_bind_param($stmt, "i", $idOnThread);
            mysqli_stmt_execute($stmt);
            $result_comments = mysqli_stmt_get_result($stmt);
            while($row_comments = mysqli_fetch_assoc($result_comments)){
                $comentatorIdHolder = $row_comments["user_id"];
                $commentContentHolder = $row_comments["content"];
                
                $patterns = array();
                    $patterns[0] = '/\n/';
                    $patterns[1] = '/\s/ui';
                $replacements = array();
                    $replacements[0] = '<br>';
                    $replacements[1] = '&nbsp';

                $dateHolder = date_create($row_comments["date"]);
                $dateFormat = date_format($dateHolder, 'd-m-Y в H:i:s');

                //SQL query for selecting user`s data posted the thread
                $sql_userNameAndPic = ("SELECT `username`, `profile_picture_name` FROM `user` WHERE `user_id`=?");
                if(!mysqli_stmt_prepare($stmt, $sql_userNameAndPic)){
                    echo "Problem selectig username and Pic from user with SQL";
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $comentatorIdHolder);
                    mysqli_stmt_execute($stmt);
                    $result_usernameAndPic = mysqli_stmt_get_result($stmt);
                    while($row_usernameAndPic = mysqli_fetch_assoc($result_usernameAndPic)){
                        $usernameHolder = $row_usernameAndPic["username"];
                        $picHolder = $row_usernameAndPic["profile_picture_name"];

                        

                        echo '<div class="comment_container">
                                <img class = "comment_pic" src="../Profile_Pictures/'.$picHolder.'">
                                <a class = "comment_username underline_remove" href="userInformation.php?page=1&id='.$comentatorIdHolder.'">'.$usernameHolder.'</a>
                                <div class="comment_text">'
                                .preg_replace($patterns, $replacements, $commentContentHolder).  
                                '</div>
                                <div class="comment_date">Постнал на: '
                                    .$dateFormat.
                                '</div>
                             </div>';                 
                    }
                }
            }
        }
    ?>  
</body>
</html>