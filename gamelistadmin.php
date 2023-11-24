<?php

use voku\helper\Paginator;

// include the composer-autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create new object pass in number of pages and identifier
$pages = new Paginator(5, 'p');

require('connect.php');

//Get game data
//Select statement to look for the specific post
$query = "SELECT COUNT(*) FROM games";
//PDO Preparation
$result = $db->prepare($query);
//Sanitize id to secure it's a number
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
//$result->bindValue('id', $id, PDO::PARAM_INT);
$result->execute();
//Fetch the selected row
$count = $result->fetch();

// get number of total records
$rowCount = $count[0];

// pass number of records to
$pages->set_total($rowCount);

$data = $db->query('SELECT * FROM games' . $pages->get_limit());

$games=array();
foreach($data as $row) {
  array_push($games, $row);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Gameopedia - Game List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <div id="all_blogs">
            <div class="blog_post">
                <?php foreach($games as $game): ?>
                    <h2><a href="delete.php?id=<?= $game['id'] ?>"><?= $game['name'] ?></a></h2>
                    <a href="edit.php?id=<?= $game['id'] ?>"> Edit </a>
                  <!--  <h2><a href="upload.php?id=<?= $game['id'] ?>"> Add Cover </a></h2> -->
                <?php endforeach ?>
            </div>
        </div>
        <p><?php echo $pages->page_links(); ?></p>
    </div>
</body>

</html>