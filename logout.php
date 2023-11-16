<?php

	if($_POST){
		
		session_start();
		session_destroy();
		header("location:login.php");
	}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logout</title>
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
                <form action="logout.php" method="post" class="mb-5 mt-5 rounded-top rounded-bottom">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                            	<label>Are you sure you want to logout?</label>
                            	<input type="submit" class="btn btn-control btn-outline-info btn-block col-sm-4" name="logout" id="logout" value="Logout">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>