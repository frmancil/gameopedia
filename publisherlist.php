<?php

require('connect.php');

//Select statement to look for the specific post
$query = "SELECT * FROM publisher";
//PDO Preparation
$result = $db->prepare($query);

$result->execute();

$publishers = $result->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gameopedia - Publisher List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
    <?php include 'navigation.php'?>
    <?php include 'verticalnav.php'?>
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <a class="nav-link link-primary" href="publisher.php">New Publisher</a>
                <?php foreach($publishers as $publisher): ?>
                    <h2><a href="publisheredit.php?id=<?= $publisher['id'] ?>"><?= $publisher['name'] ?></a></h2>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</body>

</html>