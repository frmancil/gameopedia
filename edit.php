<?php

require('connect.php');

$query = "SELECT * FROM publisher WHERE is_visible = true";
//PDO Preparation
$publisherSearch = $db->prepare($query);
//Sanitize id to secure it's a number
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

if($game){
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
}

if(isset($_POST['remove'])){
    $currentDirectory =  dirname(__FILE__);
    $uploadDirectory = "/covers/";
    $imgPath = $currentDirectory . $uploadDirectory . $_POST['cover'];
    if(file_exists($imgPath)){
        unlink($imgPath);
        $update_id = filter_input(INPUT_POST, 'gameid', FILTER_SANITIZE_NUMBER_INT);
        $update_cover = "UPDATE game_system SET cover_location =:cover WHERE game_id = :game_id";
        $update_cover = $db->prepare($update_cover);
        $update_cover->bindValue(':game_id', $update_id);
        $update_cover->bindValue(':cover', '');
        $update_cover->execute();
    }
}

function uploadImage(){
    $currentDirectory =  dirname(__FILE__);
    $uploadDirectory = "/covers/";

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName  = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];

    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    echo $uploadPath;

        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
          echo "The file " . basename($fileName) . " has been uploaded";
          header("location:gamelistadmin.php");
        } else {
          echo "An error occurred. Please contact the administrator.";
        }
    }


if ($_POST && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['description']) 
    && !empty($_POST['description']) && isset($_POST['publisher']) && !empty($_POST['publisher']) && isset($_POST['year']) && !empty($_POST['year'])) {

        $fileExtensionsAllowed = ['jpeg','jpg','png'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (in_array($fileExtension,$fileExtensionsAllowed) || !$_FILES['file']['name']) {

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

        if($insert->execute()){
            $system = filter_input(INPUT_POST, 'system', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $update_gs = "UPDATE game_system SET cover_location =:cover WHERE game_id = :game_id";
            $update_gs = $db->prepare($update_gs);
            $update_gs->bindValue(':game_id', $update_id);
            if(!empty($_FILES['file']['name'])){
                $update_gs->bindValue(':cover', $_FILES['file']['name']);
                $update_gs->execute();
                uploadImage();
            } else {
                $update_gs->bindValue(':cover', '');
                $update_gs->execute();
                echo "Success";
                header("Location: gamelistadmin.php");
                exit;
            }
        }
        } else {
            echo "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD ALL CONTENT TO THE GAME';
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
        selector: 'textarea#description',
        height: 300,
        resize: false,
        branding: false
      });
    </script>
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <button onclick="history.go(-1);">Back </button>
        <div id="all_blogs">
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Edit</legend>
                    <input type="hidden" name="gameid" id="gameid" value="<?php echo $game['id'] ?>" />
                    <input type="hidden" name="cover" id="cover" value="<?php echo $cover['cover_location'] ?>" />
                    <p>
                        <?php if($game): ?>
                            <label for="name">Game Name</label>
                            <input name="name" id="name" value="<?= $game['name'] ?>"></input>
                        <?php else: ?>
                            <label for="name">Game Name</label>
                            <input name="name" id="name"></input>
                        <?php endif ?>
                    </p>
                    <p>
                        <?php if($game): ?>
                            <label for="description">Description</label>
                        <textarea name="description" id="description"><?= $game['description'] ?></textarea>
                        <?php else: ?>
                            <label for="description">Description</label>
                            <textarea name="description" id="description"></textarea>
                        <?php endif ?>
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
                        <?php if($game): ?>
                            <label for="year">Year</label>
                            <input name="year" id="year" value="<?= $game['year'] ?>"></input>
                        <?php else: ?>
                            <label for="year">Year</label>
                            <input name="year" id="year"></input>
                        <?php endif ?>
                    </p>
                    <p>
                        Upload a File:
                        <input type="file" name="file" id="file">
                    </p>
                    <?php if($cover['cover_location']): ?>
                    <p>
                        Remove a File:
                        <input type="checkbox" id="remove" name="remove" value="remove">
                    </p>
                    <?php endif ?>
                    <p>
                        <input type="submit" name="command" value="Edit">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>