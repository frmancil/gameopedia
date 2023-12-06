 <?php
     define('DB_DSN','mysql:host=localhost;dbname=gameopedia2;charset=utf8');
     define('DB_USER','master2');
     define('DB_PASS','gamemaster');     
     
     try {
         // Try creating new PDO connection to MySQL.
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
         //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die(); // Force execution to stop on errors.
     }
 ?>