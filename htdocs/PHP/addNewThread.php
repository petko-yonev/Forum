<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/threads.css">
    <script type="text/javascript" src="../Script/script.js"></script>
    
    <title></title>
</head>
<body>

    <header>
        <?php
            include_once "header.php";
        ?>
    </header>

    <form  method="POST" action="sendNewThread.php">

        <textarea  name="NameNewThread" id="NameThread" placeholder="Име/Тема" maxlength="100" cols="30" rows="1" required></textarea>

        <textarea  name="TextNewThread" id="TextThread" placeholder="Текст" cols="30" rows="10" required></textarea>

        <div class="category_container">
            <input class="checkbox_size checkbox_checked" type="checkbox" name="games" onclick="checkbox_checked()">
            <label class="checkbox_size" >Games</label>
            <input class="checkbox_size checkbox_checked" type="checkbox" name="movies" onclick="checkbox_checked()">
            <label class="checkbox_size" >Movies</label>
            <input class="checkbox_size checkbox_checked" type="checkbox" name="news" onclick="checkbox_checked()">
            <label class="checkbox_size" >News</label>
            <input class="checkbox_size checkbox_checked" type="checkbox" name="job" onclick="checkbox_checked()">
            <label class="checkbox_size" >Job</label>
        </div>

        <?php
            if($_SESSION){
                echo '<input class="SendThreadButton" type="submit" value="Изпрати">';
            } else {
                echo '<div class="LogInToSendThreads" >Логнете се за да поствате</div>';
            }
        ?>

    </form>

    
</body>
</html>