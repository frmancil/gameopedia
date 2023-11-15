<?php

require('connect.php');

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
        $insert_query = "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, 'USER')";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $password);
        $insert->bindValue(':email', $email);
        
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

<body>
<?php include 'navigation.php'?>
<div id="wrapper">
        <div id="all_blogs">
            <form action="registration.php" method="post">
                <fieldset>
                    <legend>Registration Form</legend>
                    <p>
                        <label for="username">Username</label>
                        <input name="username" id="username">
                    </p>
                    <p>
                        <label for="password">Password</label>
                        <input name="password" id="password"></input>
                    </p>
                    <p>
                        <label for="email">Email</label>
                        <input name="email" id="email"></input>
                    </p>
                    <p>
                        <input type="submit" name="command" value="Create">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>