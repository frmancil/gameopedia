<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$query = "SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id AND games.is_visible = TRUE";
//PDO Preparation
$result = $db->prepare($query);
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
                    <div class="container">
                        <h2><a href="game.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                        <h6><?= $game['publisher'] ?> - <?= $game['year'] ?></h6>
                        <?php if($game['cover_location']): ?>
                            <img id="minicover" src="./covers/<?php echo $game['cover_location']; ?>">
                        <?php endif ?>
                    </div>
                    <hr class="double">
                <?php endwhile ?>
            </div>
        </div>
    </div>
</body>

</html>