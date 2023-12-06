<?php

require('connect.php');

if(isset($_POST['Delete'])){
    //Sanitize id to secure it's a number
    $id = filter_input(INPUT_POST, 'systemid', FILTER_SANITIZE_NUMBER_INT);
    $delete_query = "DELETE FROM system WHERE id = :id";
    $delete = $db->prepare($delete_query);
    $delete->bindValue(':id', $id);

    //Execute the update
    $delete->execute();

    //Redirect to the page with the new information
    header("Location: systemlist.php");
    exit;
}

//Select statement to look for the specific post
$querySystem = "SELECT * FROM system where id = :id";
//PDO Preparation
$resultSystem = $db->prepare($querySystem);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultSystem->bindValue(':id', $id);
$resultSystem->execute();
//Fetch the selected row
$systemEdit = $resultSystem->fetch();

if ($_POST && isset($_POST['name']) && !empty($_POST['name'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_id = filter_input(INPUT_POST, 'systemid', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $update_query = "UPDATE system SET name =:name WHERE id = :systemid";
        $update = $db->prepare($update_query);
        $update->bindValue(':name', $name);
        $update->bindValue(':systemid', $update_id);

        $update->execute();

        header("Location: systemlist.php");
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
            <form action="systemedit.php" method="post">
                <fieldset>
                    <legend>Edit</legend>
                    <input type="hidden" name="systemid" id="systemid" value="<?php echo $systemEdit['id'] ?>" />
                    <p>
                        <?php if($systemEdit): ?>
                            <label for="name">Name</label>
                            <input name="name" id="name" value="<?= $systemEdit['name'] ?>"></input>
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