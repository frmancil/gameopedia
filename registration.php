<?php

require('connect.php');

if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['repassword']) && !empty($_POST['repassword'])){
	if($_POST['password'] == $_POST['repassword']){
		if ($_POST && isset($_POST['username']) && !empty($_POST['username'])){
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$query = "SELECT * FROM users where username = :username";
	//PDO Preparation
	$result = $db->prepare($query);
	$result->bindValue(':username', $username);
	$result->execute();
	$userResult = $result->fetch();
	//Fetch the selected row
	if($userResult){
		echo 'Username already exists';
	} else {
		if ($_POST && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['email']) && !empty($_POST['email'])) {
        //  Sanitize input to escape malicious code attemps
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        //Query to update the values and bind parameters
        $insert_query = "INSERT INTO users (username, password, email, role, is_verified, verification_code) VALUES (:username, :password, :email, 'USER', false, :code)";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $password);
        $insert->bindValue(':email', $email);
        $insert->bindValue(':code', random_int(100000, 999999));
        
        //  Execute the insert
        if($insert->execute()){
            echo "Success";
            header("location:login.php");
            exit;
        }

    } else if($_POST) {
        $id = false;
        echo 'PLEASE ADD TITLE AND CONTENT TO THE POST';
        exit;
    }
	}
}
	} else {
		echo 'Entered passwords not match';
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
            <form action="registration.php" method="post">
                <fieldset>
                    <legend>Registration Form</legend>
                    <input class="form-control mb-4" placeholder="Enter your username" id="username" name="username">
                    <input type="password" class="form-control mb-4" placeholder="Enter your password" id="password" name="password">
                    <input type="password" class="form-control mb-4" placeholder="Re-enter your password" id="repassword" name="repassword">
                    <input type="email" class="form-control mb-4" placeholder="Enter your email" id="email" name="email">
                    <p>
                        <input type="submit" name="command" value="Create">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>

</html>