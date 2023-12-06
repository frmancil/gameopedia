<?php

require('connect.php');

if(isset($_GET)){
    if(isset($_GET['publisher'])){

$queryGames = "SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id AND games.publisher = :publisher";
$publisher = filter_input(INPUT_GET, 'publisher', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$resultGame = $db->prepare($queryGames);
$resultGame->bindValue(':publisher', $publisher);
$resultGame->execute();
}elseif(isset($_GET['system'])){
    $queryGames = "SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id AND game_system.system_id = :system";
$system = filter_input(INPUT_GET, 'system', FILTER_SANITIZE_NUMBER_INT);
$resultGame = $db->prepare($queryGames);
$resultGame->bindValue(':system', $system);
$resultGame->execute();
}
}

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
    <?php include 'verticalnav.php'?>
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <?php while($game = $resultGame->fetch()): ?>
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