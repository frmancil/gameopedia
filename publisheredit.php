<?php

require('connect.php');

if(isset($_POST['Delete'])){
    //Sanitize id to secure it's a number
    $id = filter_input(INPUT_POST, 'publisherid', FILTER_SANITIZE_NUMBER_INT);
    $delete_query = "DELETE FROM publisher WHERE id = :id";
    $delete = $db->prepare($delete_query);
    $delete->bindValue(':id', $id);

    //Execute the update
    $delete->execute();

    //Redirect to the page with the new information
    header("Location: publisherlist.php");
    exit;
}

//Select statement to look for the specific post
$queryPublisher = "SELECT * FROM publisher where id = :id";
//PDO Preparation
$resultPublisher = $db->prepare($queryPublisher);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultPublisher->bindValue(':id', $id);
$resultPublisher->execute();
//Fetch the selected row
$publisher = $resultPublisher->fetch();

if ($_POST && isset($_POST['name']) && !empty($_POST['name'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_id = filter_input(INPUT_POST, 'publisherid', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $update_query = "UPDATE publisher SET name =:name WHERE id = :publisherid";
        $update = $db->prepare($update_query);
        $update->bindValue(':name', $name);
        $update->bindValue(':publisherid', $update_id);

        $update->execute();

        header("Location: publisherlist.php");
        exit;

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD PUBLISHER NAME';
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gameopedia - Publisher Edit</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <button onclick="history.go(-1);">Back </button>
        <div id="all_blogs">
            <form action="publisheredit.php" method="post">
                <fieldset>
                    <legend>Edit</legend>
                    <input type="hidden" name="publisherid" id="publisherid" value="<?php echo $publisher['id'] ?>" />
                    <p>
                        <?php if($publisher): ?>
                            <label for="name">Name</label>
                            <input name="name" id="name" value="<?= $publisher['name'] ?>"></input>
                        <?php else: ?>
                            <label for="name">Name</label>
                            <input name="name" id="name"></input>
                        <?php endif ?>
                    </p>
                    <p>
                        <input type="submit" name="command" value="Edit">
                    </p>
                        <input type="submit" name="Delete" value="Delete">
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>