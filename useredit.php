<?php

require('connect.php');

//Get game data
//Select statement to look for the specific post
$queryUser = "SELECT * FROM users where id = :id";
//PDO Preparation
$resultUser = $db->prepare($queryUser);
//Sanitize id to secure it's a number
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Bind the parameter in the query to the variable
$resultUser->bindValue(':id', $id);
$resultUser->execute();
//Fetch the selected row
$user = $resultUser->fetch();

if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['repassword']) && !empty($_POST['repassword'])){
    if($_POST['password'] == $_POST['repassword']){
        if ($_POST && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['email']) && !empty($_POST['email'])) {
        
        //  Sanitize input to escape malicious code attemps
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $updateId = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);
        $isVisible = filter_input(INPUT_POST, 'isVisible', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Query to update the values and bind parameters
        $insert_query = "UPDATE users SET username =:username, password =:password, email =:email, is_visible =:isVisible, is_verified = true WHERE id = :id";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $password);
        $insert->bindValue(':email', $email);
        $insert->bindValue(':isVisible',boolval($isVisible));
        $insert->bindValue(':id', $updateId);
        
        //  Execute the insert
        if($insert->execute()){
            header("location:userlist.php");
            exit;
        }

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD USER INFORMATION';
        
    }
    } else {
        echo 'Entered passwords not match';
    }
} else if(empty($_POST['password']) && empty($_POST['repassword'])){
    if($_POST && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email']) && !empty($_POST['email'])){
        //  Sanitize input to escape malicious code attemps
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $updateId = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);
        $isVisible = filter_input(INPUT_POST, 'isVisible', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Query to update the values and bind parameters
        $insert_query = "UPDATE users SET username =:username, email =:email, is_visible =:isVisible, is_verified = true WHERE id = :id";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':email', $email);
        $insert->bindValue(':isVisible',boolval($isVisible));
        $insert->bindValue(':id', $updateId);
        
        //  Execute the insert
        if($insert->execute()){
            header("location:userlist.php");
            exit;
        }
    }else if($_POST) {
        $id = false;
        echo 'PLEASE ADD USER INFORMATION';
    }
        
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css" type="text/css">
</head>

<body class="bg-light">
<?php include 'navigation.php'?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 border shadow-lg">
            <div class="mt-5 mb-5 flex-column text-center">
                    <div class="mx-auto d-block">
                        <img src="./logo.png" alt="Logo" style="width: 80px;" class="rounded-pill">
                        <h3 class="vollkorn">Game-O-Pedia</h3>
                    </div>

            </div>
            <form action="useredit.php" method="post">
                <fieldset>
                    <input type="hidden" name="userid" id="userid" value="<?php echo $user['id'] ?>" />
                    <legend>Create New User</legend>
                    <?php if($user): ?>
                        <input class="form-control mb-4" placeholder="Enter your username" id="username" name="username" value="<?php echo $user['username'] ?>">
                    <?php else: ?>
                        <input class="form-control mb-4" placeholder="Enter your username" id="username" name="username">
                    <?php endif ?> 
                        <input type="password" class="form-control mb-4" placeholder="Enter your password" id="password" name="password">
                        <input type="password" class="form-control mb-4" placeholder="Re-enter your password" id="repassword" name="repassword">
                    <?php if($user): ?>
                        <input type="email" class="form-control mb-4" placeholder="Enter your email" id="email" name="email" value="<?php echo $user['email'] ?>">
                    <?php else: ?>
                        <input type="email" class="form-control mb-4" placeholder="Enter your email" id="email" name="email">
                    <?php endif ?> 
                    <select name="isVisible">
                        <option value=1>True</option>
                        <option value=0>False</option>
                    </select>
                    <p>
                        <input type="submit" name="command" value="Edit">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>

</html>