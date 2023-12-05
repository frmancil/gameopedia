<?php

use voku\helper\Paginator;

// include the composer-autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create new object pass in number of pages and identifier
$pages = new Paginator(1, 'p');

require('connect.php');

if(isset($_GET)){
    if(isset($_GET['search']) && (isset($_GET['category']) && empty($_GET['category']))){

    $queryGames = "SELECT COUNT(*) FROM games INNER JOIN game_system ON games.name LIKE :search AND games.id = game_system.game_id";
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $resultGame = $db->prepare($queryGames);
    $resultGame->bindValue(':search', "%$search%");
    $resultGame->execute();

    $count = $resultGame->fetch();
    $rowCount = $count[0];
    $pages->set_total($rowCount);

    $searchValue = '\'%' . $search . '%\'';
    $data = $db->query('SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.name LIKE ' . $searchValue . ' AND games.id = game_system.game_id' . $pages->get_limit());

    $games=array();
    foreach($data as $row) {
    array_push($games, $row);
    }

} elseif(isset($_GET['search']) && isset($_GET['category']) && !empty($_GET['category'])){
    $queryGames = "SELECT COUNT(*) FROM games INNER JOIN game_system ON games.name LIKE :search AND games.id = game_system.game_id AND game_system.system_id = :category";
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);
    $resultGame = $db->prepare($queryGames);
    $resultGame->bindValue(':search', "%$search%");
    $resultGame->bindValue(':category', intval($category));
    $resultGame->execute();

    $count = $resultGame->fetch();
    $rowCount = $count[0];
    $pages->set_total($rowCount);

    $searchValue = '\'%' . $search . '%\'';
    $data = $db->query('SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.name LIKE ' . $searchValue . ' AND games.id = game_system.game_id AND game_system.system_id = ' . $category . $pages->get_limit());

    $games=array();
    foreach($data as $row) {
    array_push($games, $row);
    }
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
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <?php foreach($games as $game): ?>
                    <div class="container">
                        <h2><a href="game.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                        <h6><?= $game['publisher'] ?> - <?= $game['year'] ?></h6>
                        <?php if($game['cover_location']): ?>
                            <img id="minicover" src="./covers/<?php echo $game['cover_location']; ?>">
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <p><?php echo $pages->page_links('?' . 'search=' . $_GET['search'] . '&' . 'category=' . $_GET['category'] . '&'); ?></p>
    </div>
</body>

</html>