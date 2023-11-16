<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$queryGame = "SELECT * FROM games where id = :id";
//PDO Preparation
$resultGame = $db->prepare($queryGame);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultGame->bindValue(':id', $id);
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
    <title>Gameopedia - <?= $game['name'] ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
    <script src="./vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: "textarea",
        plugins: "table code",
        toolbar: "code",
        menubar: false,
        noneditable_class: 'nonedit',
        editable_class: 'editcontent',
        min_height: 380,
        resize: false
      });
    </script>
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <button onclick="history.go(-1);">Back </button>
        <div id="all_blogs">
            <div class="blog_post">
                <h2><?= $game['name'] ?></h2>
                <div class="blog_content">
                    <textarea name="description" id="description" class="nonedit"><?= $game['description'] ?></textarea>
                        <img id="logo" src="./logos/<?php echo $system['logo_location']; ?>">
                        <img id="cover" src="./covers/<?php echo $cover['cover_location']; ?>">
                </div>
            </div>
        </div>
    </div>
</body>

</html>