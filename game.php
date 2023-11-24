<?php

use voku\helper\Paginator;

// include the composer-autoloader
require_once __DIR__ . '/vendor/autoload.php';

require('connect.php');

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

// create new object pass in number of pages and identifier
$pages = new Paginator(5, 'p');

//Get game data
//Select statement to look for the specific post
$queryPost = "SELECT COUNT(*) FROM posts WHERE game_id = :id";
//PDO Preparation
$resultPost = $db->prepare($queryPost);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultPost->bindValue(':id', $id);
$resultPost->execute();
//Fetch the selected row
$count = $resultPost->fetch();

// get number of total records
$rowCountPost = $count[0];

// pass number of records to
$pages->set_total($rowCountPost);

$data = $db->query('SELECT posts.post, users.username, posts.date, posts.is_visible FROM posts INNER JOIN users ON posts.user_id = users.id AND game_id =' . $id . ' ORDER BY date DESC' . $pages->get_limit());

$posts=array();
foreach($data as $row) {
  array_push($posts, $row);
}

if ($_POST && isset($_POST['post']) && !empty($_POST['post'])) {
        //  Sanitize input to escape malicious code attemps
        $post = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $gameId = filter_input(INPUT_POST, 'gameid', FILTER_SANITIZE_NUMBER_INT);
        $userId = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);
        
        //Query to update the values and bind parameters
        $insert_query = "INSERT INTO posts (post, user_id, game_id, is_visible) VALUES (:post, :userId, :gameId, true)";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':post', $post);
        $insert->bindValue(':gameId', $gameId);
        $insert->bindValue(':userId', $userId);
        
        //  Execute the insert
        if($insert->execute()){

            echo "Success";
            header("Location: game.php?id=" . $gameId);
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
    <meta charset="utf-8">
    <title>Gameopedia - <?= $game['name'] ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
    <script src="./vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: "textarea#description",
        plugins: "table",
        toolbar: "code",
        menubar: false,
        noneditable_class: 'nonedit',
        editable_class: 'editcontent',
        min_height: 380,
        resize: false,
        branding: false,
        menubar: false,
        readonly: true
      });
    </script>
</head>

<body>
    <?php include 'navigation.php'?>
    <div id="wrapper">
        <button onclick="history.go(-1);">Back </button>
        <div id="all_blogs">
            <div class="blog_post">
                <h2><?= $game['name'] ?></h2>
                <div class="blog_content">
                    <textarea name="description" id="description" class="nonedit"><?= $game['description'] ?></textarea>
                        <img id="logo" src="./logos/<?php echo $system['logo_location']; ?>">
                        <img id="cover" src="./covers/<?php echo $cover['cover_location']; ?>">
                </div>
                <?php if($posts): ?>
                    <?php foreach($posts as $post): ?>
                        <p><?= $post['username'] ?></p>
                        <?php $format = 'M d, Y, g:i a';
                            echo date($format, strtotime($post['date'])); ?>
                        <?php if($post['is_visible'] == true): ?>
                            <p><?= $post['post'] ?></p>
                        <?php else: ?>
                            <p>Comment deleted by admin<p>
                        <?php endif ?>    
                    <?php endforeach ?>
                <?php endif ?>
                <?php if (isset($_SESSION['logged_in'])): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER'): ?>
                        <form action="game.php?id=<?= $game['id'] ?>" method="post">
                            <fieldset>
                                <input type="hidden" name="gameid" id="gameid" value="<?php echo $game['id'] ?>" />
                                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['id'] ?>" />
                            <p>
                                <label for="post">Post a Comment</label>
                                <textarea name="post" id="post" maxlength="1000"></textarea>
                            </p>
                            <p>
                                <input type="submit" name="command" value="Post">
                            </p>
                            </fieldset>
                        </form>
                    <?php endif ?>
                <?php endif ?>
            </div>
            <p><?php echo $pages->page_links('?' . 'id=' . $game['id'] . '&'); ?></p>
        </div>
    </div>
</body>

</html>