<?php

require_once('connect.php');
//Select statement to look for the specific post
$queryPublisher = "SELECT * FROM publisher";
//PDO Preparation
$resultPublisher = $db->prepare($queryPublisher);
$resultPublisher->execute();

//Select statement to look for the specific post
$querySystem = "SELECT id, name FROM system";
//PDO Preparation
$resultSystemVer = $db->prepare($querySystem);
$resultSystemVer->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-light bg-info" style="float:left;">
  <div class="container-fluid">
<div class="navbar-collapse">
  <ul class="navbar-nav">
    <li class="nav-item">
      Browse By
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Game System</a>
      <div class="dropdown-menu">
        <?php while($systemVer = $resultSystemVer->fetch()): ?>
          <a class="dropdown-item" href="browselist.php?system=<?= $systemVer['id'] ?>"><?= $systemVer['name'] ?></a>
        <?php endwhile ?>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Publisher</a>
      <div class="dropdown-menu">
        <?php while($publisher = $resultPublisher->fetch()): ?>
          <a class="dropdown-item" href="browselist.php?publisher=<?= $publisher['name'] ?>"><?= $publisher['name'] ?></a>
        <?php endwhile ?>
    </li>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['role'] == 'ADMIN'): ?>
      <li class="nav-item">
          <a class="nav-link link-primary" href="gamelistadmin.php">Game List</a>
      </li>
      <li class="nav-item">
          <a class="nav-link link-primary" href="userlist.php">User List</a>
      </li>
      <li class="nav-item">
          <a class="nav-link link-primary" href="publisherlist.php">Publisher List</a>
      </li>
      <li class="nav-item">
          <a class="nav-link link-primary" href="systemlist.php">System List</a>
      </li>
    <?php endif ?>
  </ul>
</div>
</div>
</nav>


</body>
</html>