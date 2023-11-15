<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$queryGame = "SELECT * FROM games ORDER BY RAND() LIMIT 1";
//PDO Preparation
$resultGame = $db->prepare($queryGame);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$resultGame->execute();
//Fetch the selected row
$game = $resultGame->fetch();

//Get game image
$queryCover = "SELECT * FROM game_system where game_id = :game_id";
//PDO Preparation
$resultCover = $db->prepare($queryCover);
//Sanitize id to secure it's a number
//Bind the parameter in the query to the variable
$resultCover->bindValue(':game_id', $game['id']);
$resultCover->execute();
//Fetch the selected row
$cover = $resultCover->fetch();

//Get game system
$system = "SELECT * FROM system where id = :system_id";
//PDO Preparation
$resultSystem = $db->prepare($system);
//Sanitize id to secure it's a number
//Bind the parameter in the query to the variable
$resultSystem->bindValue(':system_id', $cover['system_id']);
$resultSystem->execute();
//Fetch the selected row
$system = $resultSystem->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gameopedia - Home Page</title>
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
                <h2><?= $game['name'] ?></h2>
                <div class="blog_content">
                    <?= $game['description'] ?>
                        <img id="logo" src="./logos/<?php echo $system['logo_location']; ?>">
                        <img id="cover" src="./covers/<?php echo $cover['cover_location']; ?>">
                </div>
            </div>
        </div>
    </div>
</body>

</html>