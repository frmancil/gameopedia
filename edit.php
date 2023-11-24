<?php

require('connect.php');

$query = "SELECT * FROM publisher WHERE is_visible = true";
//PDO Preparation
$publisherSearch = $db->prepare($query);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$publisherSearch->execute();

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

if ($_POST && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['description']) 
    && !empty($_POST['description']) && isset($_POST['publisher']) && !empty($_POST['publisher']) && isset($_POST['year']) && !empty($_POST['year'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $publisher = filter_input(INPUT_POST, 'publisher', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
        $update_id = filter_input(INPUT_POST, 'gameid', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $insert_query = "UPDATE games SET name =:name, description =:description, publisher =:publisher, year =:year WHERE id = :gameid";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':name', $name);
        $insert->bindValue(':description', $description);
        $insert->bindValue(':publisher', $publisher);
        $insert->bindValue(':year', $year);
        $insert->bindValue(':gameid', $update_id);

        $insert->execute();

        header("Location: gamelistadmin.php");
        exit;

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD CONTENT TO THE GAME';
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gameopedia - New Game</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
    <script src="./vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#description',
        height: 300,
        resize: false
      });
    </script>
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <button onclick="history.go(-1);">Back </button>
        <div id="all_blogs">
            <form action="edit.php" method="post">
                <fieldset>
                    <legend>Edit</legend>
                    <input type="hidden" name="gameid" id="gameid" value="<?php echo $game['id'] ?>" />
                    <p>
                        <label for="name">Game Name</label>
                        <input name="name" id="name" value="<?= $game['name'] ?>"></input>
                    </p>
                    <p>
                        <label for="description">Description</label>
                        <textarea name="description" id="description"><?= $game['description'] ?></textarea>
                    </p>
                    <p>
                        <label for="publisher">Publisher</label>
                        <select name="publisher">
                            <?php while ($post = $publisherSearch->fetch()) : ?>
                                <option value="<?= $post['name'] ?>"><?= $post['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </p>
                    <p>
                        <label for="year">Year</label>
                        <input name="year" id="year" value="<?= $game['year'] ?>"></input>
                    </p>
                    <p>
                        <input type="submit" name="command" value="Edit">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>