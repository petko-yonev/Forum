<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/settings.css">
    <link rel="stylesheet" type="text/css" href="../CSS/threads.css">
    <script src="../Script/script.js" ></script>
    <title>OverhoulForum</title>
</head>
<body>
    <header>
        <?php
            include_once "header.php";
        ?>
    </header>

    <div class="Settings_Threads_buttons_cantainer">
        <br>
        <button class="Settings_Threads_buttons" onclick="ShowAccountInfo()">Информация за акаунта</button>
        <button class="Settings_Threads_buttons" onclick="ShowYourThreads()">Твоите постове</button>
        <button class="Settings_Threads_buttons" onclick="ShowUserData()">Данни на потребителя</button>
    </div>

    <?php
        include_once "sql_connection.php";
        $user_id = $_SESSION["userId"];

        //SQL query for user data
        $sql_takeData = "SELECT `username`, `password`, `about_me`, `profile_picture_name` FROM `user` WHERE `user_id`= ?";

        if(!mysqli_stmt_prepare($stmt, $sql_takeData)){
            echo "Problem selecting User Data from user with SQL";
        } else {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result_data = mysqli_stmt_get_result($stmt);
            while($row_data = mysqli_fetch_assoc($result_data)){
            
                //Form for changing user`s Picture and/or information
                if($_GET["show"] === "info"){ 
                echo '<form action="sendNewAccountSetting_PicAndInfo.php" class="AccountInfo" method="POST" enctype="multipart/form-data"> 
                        <div class = "inputContainerInfo">
                            <img  alt="PIC" class="preview_new_pic" id = "preview_new_pic">
                            <img  alt="PIC" class="settings_pic" src="../Profile_Pictures/'.$row_data["profile_picture_name"].'">
                        </div>
                        <div class="Settings_Threads_buttons_cantainer">
                            <input class="Settings_Threads_buttons" type="file" id="file" name="file" accept="image/*" onchange="previewPic(event);"> 
                            <button class="Change_Profile_pic" type="submit"  name="submit">Промени снимката</button>
                            <button class="Settings_Threads_buttons" type="submit"  name="delete_pic">Изтрии снимката</button>
                        </div>
                        <div class = "inputContainerInfo">
                            <textarea  name="about_user" class="text_about_me" placeholder="За мен.." >'.$row_data["about_me"].'</textarea>
                        </div>
                        <div class="Settings_Threads_buttons_cantainer">
                            <input class="Settings_Threads_buttons" name = "change_user_info" type="submit" value="Промени Описанието">
                        </div>
                    </form>';
                }    
                //Form for changing user`s Username and/or Password 
                if($_GET["show"] === "data"){
                echo '<form action="sendNewAccountSetting_ChangeUserPassword.php" class="AccountData" method="POST">
                        <div class = "inputContainerData">
                            <input type="text" name="newName" class="profile_settings" value="'.$row_data["username"].'">
                            <input type="text" name="newNameRepeat" class="profile_settings" placeholder="Променете името си по горе и го повторете тук">
                        </div>
                        <div class = "inputContainerData">
                            <input type="password" name="newPassword" class="PsswordVisibility" placeholder="Нова Парола" >
                            <input type="password" name="newPasswordRepeat" class="PsswordVisibility" placeholder="Променете паролата си по горе и я повторете тук">
                        </div>
                        <div class="red_border">Задължително поле за попълване при промяна на парола или име.
                            <input type="password" name="oldPasswordConfirm" class="profile_settings" placeholder="Стара Парола" autocomplete="off">
                            <input type="email" name="emailConfirm" class="profile_settings" placeholder="Имейл за потвърждение" autocomplete="off">
                        </div>
                        <div class="Settings_Threads_buttons_cantainer">
                            <input class="Settings_Threads_buttons" id = "ShowPassBtn" onclick="ShowPass()" type="button" value="Покажи Паролите">
                            <input class="Settings_Threads_buttons" type="submit" value="Промени Данните">
                        </div>
                    </form>';
                }  
            }
        }
    
        //SQL for taking users threads
        if($_GET["show"] === "threads"){
            $threadsOnPage = 10;
            $pageNum = $_GET["page"];
            $firstResultOnThisPage = ($pageNum - 1) * $threadsOnPage;
            $sql_takeUserThreads = "SELECT `thread_id`, `name_thread`, `date` FROM `threads` WHERE user_id = ? ORDER BY `thread_id` DESC LIMIT ". $firstResultOnThisPage . ',' . $threadsOnPage;
            if(!mysqli_stmt_prepare($stmt, $sql_takeUserThreads)){
                echo "Problem selecting Name thread and date from threads with SQL";
            } else {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result_UserThreads = mysqli_stmt_get_result($stmt);
                echo '<article class="ShowThreads"> <table>';
                    while($row_userThreads = mysqli_fetch_assoc($result_UserThreads)){

                        echo'<tr class="test">
                                <td class="nameThread" > <a class="underline_remove" href="threadTemplate.php?idOnThread='.$row_userThreads["thread_id"].'"> '.$row_userThreads["name_thread"].' </a> </td>
                                <td class="dateThread" > <div> '.$row_userThreads["date"].' </div> </td>
                            </tr>';
                    }
                echo'</table> </article>';
                }
            //SQL for counting users threads
            $sql_numThreadsOnUser = ("SELECT COUNT(`thread_id`) FROM `threads` WHERE user_id = $user_id ");

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
            }
            
            // echo '<br><div class="pages">';

            //     $link = "window.location.href='accountSettings.php?page=".$pageNum."&show=threads'";
            //     echo'<input type="hidden" id="lastPage" value="'.$lastPage.'"></input>
            //         <input name="pageInput" class="pageInput" onkeyup="test()" value="'.$pageNum.'"></input>';

            //     if($pageNum != $lastPage && $lastPage != 0){
            //         $link = "window.location.href='accountSettings.php?page=".$lastPage."&show=threads'";
            //         echo '...<button class="pageBtn" onclick="'.$link.';"> '.$lastPage.' </button>';
            //     } 

            // echo'</div>';
            
    ?>
</body>
</html>