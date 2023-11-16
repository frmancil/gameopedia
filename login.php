<?php

require('connect.php');


    if ($_POST && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $query = "SELECT * FROM users where username = :username";
            $result = $db->prepare($query);
            $result->bindValue(':username', $username);
            $result->execute();
            $userResult = $result->fetch();

            if(!$userResult){
                echo 'Username not found';
            } else {
                if(password_verify($_POST['password'], $userResult['password'])){
                    session_start();
                    $_SESSION['username'] = $userResult['username'];
                    $_SESSION['role'] = $userResult['role'];
                    $_SESSION['verified'] = $userResult['is_verified'];
                    if($_SESSION['verified'] == true){
                        $_SESSION['logged_in'] = true;
                        header("location:index.php");
                    } else {
                        header("location:verification.php");
                    }
                } else {
                    echo 'Wrong password';
                }
            }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>login</title>
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
                <form action="login.php" method="post" class="mb-5 mt-5 rounded-top rounded-bottom">
                    <div class="form-group">
                    <input class="form-control mb-4" placeholder="Enter your username" id="username" name="username">
                    <input type="password" class="form-control mb-4" placeholder="Enter your password" id="password" name="password">
                    </div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <button class="btn btn-control btn-outline-info btn-block col-sm-4">login</button>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <p class="float-right">Don't have an account? </p>
                                <a href="registration.php" class="signin">Register</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>