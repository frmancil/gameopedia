<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$query = "SELECT * FROM games ORDER BY date_added DESC LIMIT 5";
//PDO Preparation
$result = $db->prepare($query);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$result->execute();
//Fetch the selected row
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
                <?php while ($game = $result->fetch()) : ?>
                <div class="blog_post">
                    <h2><a href="game.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                    </div>
                <?php endwhile ?>
            </div>
        </div>
    </div>
</body>

</html>