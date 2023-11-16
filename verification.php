<?php

require('connect.php');


    if ($_POST && isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['code']) && !empty($_POST['code'])){
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $query = "SELECT * FROM users where username = :username";
            $result = $db->prepare($query);
            $result->bindValue(':username', $username);
            $result->execute();
            $userResult = $result->fetch();

            if(!$userResult){
                echo 'Username not found';
            } else {
                echo $userResult['verification_code'];
                echo $_POST['code'];
                if($userResult['verification_code'] == $_POST['code']){
                    $update_query = "UPDATE users SET is_verified = true WHERE username = :username";
                    $update = $db->prepare($update_query);
                    $update->bindValue(':username', $username);
                    $update->execute();
                    session_start();
                    $_SESSION['username'] = $userResult['username'];
                    $_SESSION['role'] = $userResult['role'];
                    $_SESSION['logged_in'] = true; 
                    header("location:index.php");
                } else {
                    echo 'Wrong code';
                }
            }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verification Code</title>
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
                <form action="verification.php" method="post" class="mb-5 mt-5 rounded-top rounded-bottom">
                    <div class="form-group">
                    <input class="form-control mb-4" placeholder="Enter your username" id="username" name="username">
                    <input class="form-control mb-4" placeholder="Enter your code" id="code" name="code" maxlength="6">
                    </div>
                    <div class="row justify-content-center">
                            <div class="col-sm-6">
                    <input type="submit" class="btn btn-control btn-outline-info btn-block col-sm-4" name="accept" id="accept" value="Accept">
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>