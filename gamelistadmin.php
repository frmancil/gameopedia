<?php

require('connect.php');

if($_POST && isset($_POST['sort']) && !empty($_POST['sort'])){

$sortedQuery = 'SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id ORDER BY ' . $_POST['sort'] . '  ASC';
$resultSort = $db->prepare($sortedQuery);
$resultSort->execute();
//Fetch the selected row
$games = $resultSort->fetchAll();

$sorted = $_POST['sort'];

} else {

$sortedQuery = ('SELECT games.id, games.name, games.publisher, games.year, game_system.cover_location FROM games INNER JOIN game_system ON games.id = game_system.game_id');
$resultSort = $db->prepare($sortedQuery);
$resultSort->execute();
//Fetch the selected row
$games = $resultSort->fetchAll();
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
                <form action="gamelistadmin.php" method="POST">
                    <?php if($_SESSION['logged_in']): ?>
                    <select onchange="this.form.submit();" name="sort">
                        <option value="">Sort By</option>
                        <option value='name'>Name</option>
                        <option value='year'>Created</option>
                        <option value='date_added'>Date Updated</option>
                    </select>
                <?php endif ?>
                <?php if(isset($_POST['sort'])): ?>
                    <?php if($sorted == 'date_added'): ?>
                    <p>Sorted By Updated</p>
                    <?php else: ?>
                    <p>Sorted By <?= $sorted ?></p>
                   <?php endif ?>  
                <?php endif ?>
                <?php foreach($games as $game): ?>
                    <h2><a href="delete.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                    <h6><?= $game['publisher'] ?> - <?= $game['year'] ?></h6>
                    <?php if($game['cover_location']): ?>
                        <img id="minicover" src="./covers/<?php echo $game['cover_location']; ?>">
                    <?php endif ?>
                    <a href="edit.php?id=<?= $game['id'] ?>"> Edit </a> 
                  <hr class="double">
                <?php endforeach ?>
            </div>
        </div>
    </div>
</body>

</html>