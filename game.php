<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$query = "SELECT * FROM games where id = 23";
//PDO Preparation
$result = $db->prepare($query);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$result->execute();
//Fetch the selected row
$game = $result->fetch();

//Get game system
$system = "SELECT * FROM system where id = 5";
//PDO Preparation
$resultSystem = $db->prepare($system);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$resultSystem->execute();
//Fetch the selected row
$systemResult = $resultSystem->fetch();

//Get game image
$image = "SELECT * FROM game_system where game_id = 23 and system_id = 5";
//PDO Preparation
$resultImage = $db->prepare($image);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$resultImage->execute();
//Fetch the selected row
$imageResult = $resultImage->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gameopedia - <?= $game['name'] ?></title>
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <h2><?= $game['name'] ?></h2>
                <div class="blog_content">
                    <?= $game['description'] ?>
                        <img id="logo" src="./logos/<?php echo $systemResult['logo_location']; ?>">
                        <img id="cover" src="./covers/<?php echo $imageResult['cover_location']; ?>">
                </div>
            </div>
        </div>
    </div>
</body>

</html>