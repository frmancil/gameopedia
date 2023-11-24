<?php

require('connect.php');

$query = "SELECT * FROM publisher WHERE is_visible = true";
//PDO Preparation
$publisherSearch = $db->prepare($query);
$publisherSearch->execute();

if ($_POST && isset($_POST['name']) && !empty($_POST['name']) ) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $query = "SELECT * FROM publisher where name = :name";
        //PDO Preparation
        $result = $db->prepare($query);
        $result->bindValue(':name', $name);
        $result->execute();
        $publisherResult = $result->fetch();
    //Fetch the selected row
    if($publisherResult){
        echo 'Publisher already exists';
    } else {
        //Query to update the values and bind parameters
        $insert_query = "INSERT INTO publisher (name, is_visible) VALUES (:name, true)";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':name', $name);
        
        //  Execute the insert
        if($insert->execute()){
            echo "Success";
            header("Location: index.php");
            exit;
        }
}
    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD PUBLISHER NAME';
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
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <div id="all_blogs">
            <form action="publisher.php" method="post">
                <fieldset>
                    <legend>Publisher</legend>
                    <p>
                        <label for="name">Name</label>
                        <input name="name" id="name">
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