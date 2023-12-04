<?php

require('connect.php');

if($_POST && isset($_POST['sort']) && !empty($_POST['sort'])){

$data = $db->query('SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id ORDER BY ' . $_POST['sort'] . '  ASC');

$games=array();
$sorted = $_POST['sort'];
foreach($data as $row) {
  array_push($games, $row);
}

} else {

$data = $db->query('SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id');

$games=array();
foreach($data as $row) {
  array_push($games, $row);
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
                <form action="gamelist.php" method="POST">
                    <?php if($_SESSION): ?>
                    <select onchange="this.form.submit();" name="sort">
                        <option value="">Sort By</option>
                        <option value='name'>Name</option>
                        <option value='year'>Created</option>
                        <option value='date_added'>Date Updated</option>
                    </select>
                    <?php endif ?>
            <div class="blog_post">
                <?php if($sorted == 'date_added'): ?>
                    <p>Sorted By Updated</p>
                    <?php else: ?>
                    <p>Sorted By <?= $sorted ?></p>
                   <?php endif ?> 
                <?php foreach($games as $game): ?>
                    <div class="container">
                        <h2><a href="game.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                        <h6><?= $game['publisher'] ?> - <?= $game['year'] ?></h6>
                        <?php if($game['cover_location']): ?>
                            <img id="minicover" src="./covers/<?php echo $game['cover_location']; ?>">
                        <?php endif ?>
                    </div>
                    <hr class="double">
                <?php endforeach ?>
            </div>
        </form>
        </div>
    </div>
</body>

</html>