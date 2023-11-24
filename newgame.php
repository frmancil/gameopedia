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

if ($_POST && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['description']) 
    && !empty($_POST['description']) && isset($_POST['publisher']) && !empty($_POST['publisher']) && isset($_POST['year']) && !empty($_POST['year'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $publisher = filter_input(INPUT_POST, 'publisher', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $insert_query = "INSERT INTO games (name, description, publisher, year, is_visible) VALUES (:name, :description, :publisher, :year, true)";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':name', $name);
        $insert->bindValue(':description', $description);
        $insert->bindValue(':publisher', $publisher);
        $insert->bindValue(':year', $year);
        
        //  Execute the insert
        if($insert->execute()){
            $last_id = $db->lastInsertId();
            $insert_gs = "INSERT INTO game_system (game_id, system_id, cover_location) VALUES (:game_id, :system_id, :cover_location)";
            $insert_gs = $db->prepare($insert_gs);
            $insert_gs->bindValue(':game_id', $last_id);
            $insert_gs->bindValue(':system_id', 5);
            $insert_gs->bindValue(':cover_location', $last_id . '-' . 5 . '.jpg');
            $insert_gs->execute();

            echo "Success";
            header("Location: index.php");
            exit;
        }

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD TITLE AND CONTENT TO THE POST';
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
        <div id="all_blogs">
            <form action="newgame.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>New Game</legend>
                    <p>
                        <label for="name">Name</label>
                        <input name="name" id="name">
                    </p>
                    <p>
                        <label for="description">Description</label>
                        <textarea name="description" id="description"></textarea>
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
                        <input name="year" id="year"></input>
                    </p>
                    <p>
                        <input type="submit" name="command" value="Create">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>