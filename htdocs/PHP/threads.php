<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
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
    
    <article>
        <h1>Игри
            <?php
                // Check if u are logged in
                if($_SESSION){
                    echo '<a href = "addNewThread.php"><button class="add_thread_place">Добави нова тема</button></a> ';
                 } else {
                    echo '<div class="add_thread_place" >Логнете се за да поствате</div>';
                 }
            ?>
        </h1>
            <?php

            include_once "sql_connection.php"; 

            $pageNum = $_GET["page"];
            $pageCategory = $_GET["category"];
            $threadsOnPage = 10;
            $firstResultOnThisPage = ($pageNum - 1) * $threadsOnPage;

            //SQL query for selecting threads with selected category
            $sql_threads = "SELECT `thread_id`,`name_thread`,`date` FROM `threads` INNER JOIN `threads_category` ON `threads`.thread_id = `threads_category`.threads_id WHERE category_id = $pageCategory ORDER BY `thread_id` DESC LIMIT ". $firstResultOnThisPage . ',' . $threadsOnPage ;
                if(!mysqli_stmt_prepare($stmt, $sql_threads)){
                    echo "Problem with loadig threads";
                } else {
                    mysqli_stmt_execute($stmt);
                    $result_threads = mysqli_stmt_get_result($stmt);
                    if($result_threads->num_rows > 0){

                        echo '<table>';
                        while($row_threads = mysqli_fetch_assoc($result_threads)){
                            $idOnThread = $row_threads["thread_id"];
                            $nameOnThread = $row_threads["name_thread"];
                            $dateHolder = date_create($row_threads["date"]);
                            $dateFormat = date_format($dateHolder, 'd-m-Y H:i:s');

                            echo '<tr>
                                    <td class="nameThread" > <a class="underline_remove" href="threadTemplate.php?idOnThread='.$idOnThread.'"> '.$nameOnThread.' </a> </td>
                                    <td class="dateThread" > <div> '.$dateFormat.' </div> </td>
                                </tr>';
                        }
                    } else {
                        echo "Няма резултати за показване";
                    }
                        echo "</table>";
                } 
            ?>   
    </article>

    <?php
    //SQL query for selecting the number of threads with that category
    $sql_numThreadsOnCategory = ("SELECT COUNT(`threads_id`) FROM `threads_category` WHERE `category_id` = $pageCategory");

    if(!mysqli_stmt_prepare($stmt, $sql_numThreadsOnCategory)){
        echo "Problem selectring Max thread ID from threads with SQL"; 
    } else {
        mysqli_stmt_execute($stmt);
        $numThreadsOnCategory = mysqli_stmt_get_result($stmt);
        echo '<br><div class="pages">';
        while($row_numThreads = mysqli_fetch_assoc($numThreadsOnCategory)){
            $numThreads = $row_numThreads["COUNT(`threads_id`)"];
            $lastPage = ceil($numThreads / $threadsOnPage);
            $firstpage = 1;

            if($pageNum != 1 ){
                $link = "window.location.href='threads.php?page=".$firstpage."&category=".$pageCategory."'";
                echo '<button class="pageBtn" onclick="'.$link.';"> '.$firstpage.' </button>...';
            }
            if($pageNum > 5 ){
                $lowerPage = $pageNum - 5;
                $link = "window.location.href='threads.php?page=".$lowerPage."&category=".$pageCategory."'";
                echo ',<button class="pageBtn" onclick="'.$link.';"> -5 </button>';
            }
            if($pageNum > 2 ){
                $lowerPage = $pageNum - 1;
                $link = "window.location.href='threads.php?page=".$lowerPage."&category=".$pageCategory."'";
                echo ',<button class="pageBtn" onclick="'.$link.';"> -1 </button>';
            }
                $link = "window.location.href='threads.php?page=".$pageNum."&category=".$pageCategory."'";
                echo'<input type="hidden" id="pageCategory" value="'.$pageCategory.'"></input>
                    <input type="hidden" id="lastPage" value="'.$lastPage.'"></input>
                    <input name="pageInput" class="pageInput" onkeyup="changePageThreads()" value="'.$pageNum.'"></input>';

            if($pageNum < $lastPage - 1 ){
                $incPage = $pageNum + 1;
                $link = "window.location.href='threads.php?page=".$incPage."&category=".$pageCategory."'";
                echo ',<button class="pageBtn" onclick="'.$link.';"> +1 </button>';
            }
            if($pageNum < $lastPage - 5 ){
                $incPage = $pageNum + 5;
                $link = "window.location.href='threads.php?page=".$incPage."&category=".$pageCategory."'";
                echo ',<button class="pageBtn" onclick="'.$link.';"> +5 </button>';
            }
            if($pageNum != $lastPage && $lastPage != 0){
                $link = "window.location.href='threads.php?page=".$lastPage."&category=".$pageCategory."'";
                echo '...<button class="pageBtn" onclick="'.$link.';"> '.$lastPage.' </button>';
            } 
        }
        echo'</div>'; 
    }         
    ?>
</body>
</html>