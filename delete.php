<?php

require('connect.php');

if(isset($_POST['Delete'])){
    //Sanitize id to secure it's a number
    $id = filter_input(INPUT_POST, 'gameid', FILTER_SANITIZE_NUMBER_INT);
    $delete_query = "DELETE FROM game_system WHERE game_id = :id";
    $delete = $db->prepare($delete_query);
    $delete->bindValue(':id', $id);

    //Execute the update
    if($delete->execute()){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $delete_game_query = "DELETE FROM games WHERE id = :id";
        $delete_game = $db->prepare($delete_game_query);
        $delete_game->bindValue(':id', $id);

        //Execute the update
        $delete_game->execute();
    }
    
    //Redirect to the page with the new information
    header("Location:gamelistadmin.php");
    exit;
}

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

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Query to Paginate, deleted the rest of the pagination logic
$data = $db->query('SELECT posts.post, users.username, posts.date, posts.is_visible, posts.id FROM posts INNER JOIN users ON posts.user_id = users.id AND game_id =' . $id . ' AND posts.is_visible ORDER BY date DESC');

$posts=array();
foreach($data as $row) {
  array_push($posts, $row);
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
            <form action="delete.php?id=<?= $game['id'] ?>" method="post">
                <input type="hidden" name="gameid" id="gameid" value="<?php echo $game['id'] ?>" />
                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['id'] ?>" />
                <fieldset>
            <div class="blog_post">
                <div>
                <h2><?= $game['name'] ?></h2>
                    <input type="submit" name="Delete" value="Delete Game">
                </div>
                <div class="blog_content">
                    <textarea name="description" id="description" class="nonedit"><?= $game['description'] ?></textarea>
                        <img id="logo" src="./logos/<?php echo $system['logo_location']; ?>">
                        <?php if($cover['cover_location']): ?>
                        <img id="cover" src="./covers/<?php echo $cover['cover_location']; ?>">
                        <?php endif ?>
                    <?php if($posts): ?>
                    <?php foreach($posts as $post): ?>
                        <p><?= $post['username'] ?></p>
                        <?php $format = 'M d, Y, g:i a';
                            echo date($format, strtotime($post['date'])); ?>
                        <p><?= $post['post'] ?></p>
                        <input type="hidden" name="postid" id="postid" value="<?php echo $post['id'] ?>" />
                        <a href="postedit.php?id=<?= $post['id'] ?>">Edit</a>             
                    <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
           </fieldset> 
        </form>
        </div>
    </div>
</body>

</html>