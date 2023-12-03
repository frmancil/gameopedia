<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$query = "SELECT * FROM games";
//PDO Preparation
$result = $db->prepare($query);
//Sanitize id to secure it's a number
$result->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gameopedia - Game List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <?php while($game = $result->fetch()): ?>
                    <h2><a href="delete.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                    <a href="edit.php?id=<?= $game['id'] ?>"> Edit </a> 
                  <!--  <h2><a href="upload.php?id=<?= $game['id'] ?>"> Add Cover </a></h2> -->
                <?php endwhile ?>
            </div>
        </div>
    </div>
</body>

</html>