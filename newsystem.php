<?php

require('connect.php');

if ($_POST && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['company']) 
    && !empty($_POST['company'])) {
        //  Sanitize input to escape malicious code attemps
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //Query to update the values and bind parameters
        $insert_query = "INSERT INTO system (name, company, logo_location) VALUES (:name, :company, :logoLocation)";
        $insert = $db->prepare($insert_query);
        $insert->bindValue(':name', $name);
        $insert->bindValue(':company', $company);
        $insert->bindValue(':logoLocation', $name . '.jpg');
        
        //  Execute the insert
        if($insert->execute()){
            echo "Success";
            header("Location: index.php");
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Gameopedia - New System</title>
</head>

<body>
    <div id="wrapper">
        <div id="all_blogs">
            <form action="newsystem.php" method="post">
                <fieldset>
                    <legend>New System</legend>
                    <p>
                        <label for="name">Name</label>
                        <input name="name" id="name">
                    </p>
                    <p>
                        <label for="company">Company</label>
                        <input name="company" id="company"></input>
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