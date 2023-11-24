<?php

require('connect.php');

if(isset($_POST['Delete'])){
    //Sanitize id to secure it's a number
    $id = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
    $delete_query = "UPDATE posts SET is_visible = false WHERE id = :id";
    $delete = $db->prepare($delete_query);
    $delete->bindValue(':id', $id);

    //Execute the update
    $delete->execute();

    //Redirect to the page with the new information
    header("Location: delete.php?id=" . $_POST['gameid']);
    exit;
}



if(isset($_POST['Undelete'])){
    //Sanitize id to secure it's a number
    $id = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
    $delete_query = "UPDATE posts SET is_visible = true WHERE id = :id";
    $delete = $db->prepare($delete_query);
    $delete->bindValue(':id', $id);

    //Execute the update
    $delete->execute();

    //Redirect to the page with the new information
    header("Location: delete.php?id=" . $_POST['gameid']);
    exit;
}

//Select statement to look for the specific post
$queryPost = "SELECT * FROM posts where id = :id";
//PDO Preparation
$resultPost = $db->prepare($queryPost);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultPost->bindValue(':id', $id);
$resultPost->execute();
//Fetch the selected row
$post = $resultPost->fetch();

if ($_POST && isset($_POST['post']) && !empty($_POST['post'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $update_id = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $insert_query = "UPDATE posts SET post =:post WHERE id = :postid";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':post', $name);
        $insert->bindValue(':postid', $update_id);

        $insert->execute();

        header("Location: delete.php?id=" . $_POST['gameid']);
        exit;

    } else if($_POST) {
        $id = false;
        echo 'DO NOT REMOVE POST INFORMATION';
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gameopedia - Edit Post</title>
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
            <form action="postedit.php" method="post">
                <fieldset>
                    <legend>Edit</legend>
                    <input type="hidden" name="gameid" id="gameid" value="<?php echo $post['game_id'] ?>" />
                    <input type="hidden" name="postid" id="postid" value="<?php echo $post['id'] ?>" />
                    <p>
                        <?php if($post): ?>
                            <label for="post">Post</label>
                            <input name="post" id="post" value="<?= $post['post'] ?>"></input>
                        <?php else: ?>
                            <label for="post">Post</label>
                            <input name="post" id="post"></input>
                        <?php endif ?>
                    </p>
                    <p>
                        <input type="submit" name="command" value="Edit">
                    </p>
                    <?php if($post['is_visible'] == true): ?>
                        <input type="submit" name="Delete" value="Hide">
                    <?php else: ?>
                        <input type="submit" name="Undelete" value="Unhide">
                    <?php endif ?>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>