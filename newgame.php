<?php

require('connect.php');

$query = "SELECT * FROM publisher WHERE is_visible = true";
//PDO Preparation
$publisherSearch = $db->prepare($query);

$publisherSearch->execute();

$systemQuery = "SELECT * FROM system";
//PDO Preparation
$systemSearch = $db->prepare($systemQuery);

$systemSearch->execute();

function uploadImage(){
    $currentDirectory =  dirname(__FILE__);
    $uploadDirectory = "/covers/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed 

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName  = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    echo $uploadPath;

      if (! in_array($fileExtension,$fileExtensionsAllowed)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
      }

      if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
          echo "The file " . basename($fileName) . " has been uploaded";
          header("location:gamelistadmin.php");
        } else {
          echo "An error occurred. Please contact the administrator.";
        }
      } else {
        foreach ($errors as $error) {
          echo "\n" . $error . "\n";
        }
      }
    }

if ($_POST && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['description']) 
    && !empty($_POST['description']) && isset($_POST['publisher']) && !empty($_POST['publisher']) && isset($_POST['year']) && !empty($_POST['year']) && isset($_POST['system']) && !empty($_POST['system'])) {
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
            $system = filter_input(INPUT_POST, 'system', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $insert_gs = "INSERT INTO game_system (game_id, system_id, cover_location) VALUES (:game_id, :system_id, :cover)";
            $insert_gs = $db->prepare($insert_gs);
            $insert_gs->bindValue(':game_id', $last_id);
            $insert_gs->bindValue(':system_id', $system);
            if(!empty($_FILES['file']['name'])){
                $insert_gs->bindValue(':cover', $_FILES['file']['name']);
                $insert_gs->execute();
                uploadImage();
            } else {
                $insert_gs->bindValue(':cover', '');
                $insert_gs->execute();
                echo "Success";
                header("Location: gamelistadmin.php");
                exit;
            }
        }

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD GAME INFORMATION';
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
                            <?php while ($publisher = $publisherSearch->fetch()) : ?>
                                <option value="<?= $publisher['name'] ?>"><?= $publisher['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </p>
                    <p>
                        <label for="system">Game System</label>
                        <select name="system">
                            <?php while ($system = $systemSearch->fetch()) : ?>
                                <option value="<?= $system['id'] ?>"><?= $system['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </p>
                    <p>
                        <label for="year">Year</label>
                        <input name="year" id="year"></input>
                    </p>
                    <p>
                        Upload a File:
                        <input type="file" name="file" id="file">
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