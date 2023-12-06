<?php
    session_start();

    require_once('connect.php');
    //Select statement to look for the specific post
    $querySystem = "SELECT id, name FROM system";
    //PDO Preparation
    $resultSystemNav = $db->prepare($querySystem);
    $resultSystemNav->execute();

    if($_POST && isset($_POST['search'])){
        $search = $_POST['search'];
        header("Location: searchresult.php?search=<?= $search ?>");
    } elseif($_POST && isset($_POST['search']) && isset($_POST['category'])) {
        $search = $_POST['search'];
        $category = $_POST['category'];
        header("Location: searchresult.php?search=<?= $search ?>&category=<?= $category ?>");
    }
?>

<nav class="navbar navbar-expand-sm navbar-light bg-info p-2 mb-5">
    <div class="container-fluid">
        <a class="navbar-brand d-block mx-auto text-center" href="index.php"> Game-O-Pedia
            <img src="./logo.png" alt="Logo" style="width:80px;" class="rounded-pill">
        </a>
        <form class="form-inline" action="searchresult.php">
            <input class="form-control mr-sm-2" type="text" id="search" name="search" placeholder="Search">
            <select id="category" name="category">
                        <option value="">Sort By</option>
                        <?php while($systemNav = $resultSystemNav->fetch()): ?>
                            <option value='<?= $systemNav['id'] ?>'><?= $systemNav['name'] ?></option>
                        <?php endwhile ?>
                    </select>
            <button class="btn btn-success" type="submit">Search</button>
        </form>

        <div class="collapse navbar-collapse m-3" id="mynavbar">

            <ul class="navbar-nav ms-auto px-4">
                <?php if (isset($_SESSION['logged_in'])): ?>
                    Username: <?= $_SESSION['username'] ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN'): ?>
                        <li class="nav-item">
                            <a class="nav-link link-success" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="gamelistadmin.php">Game List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="newgame.php">New Game</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="userlist.php">User List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="usercreate.php">New User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="publisher.php">New Publisher</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="publisherlist.php">Publisher List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="newsystem.php">New System</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="systemlist.php">System List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="logout.php">Logout</a>
                        </li>
                        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'USER'): ?>
                        <li class="nav-item">
                            <a class="nav-link link-success" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="gamelist.php">Game List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-primary" href="logout.php">Logout</a>
                        </li>
                    <?php endif ?>
                <?php endif ?>
                <?php if (!isset($_SESSION['logged_in'])): ?>
                <li class="nav-item">
                    <a class="nav-link link-success" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-primary" href="gamelist.php">Game List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-primary" href="registration.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-primary" href="login.php">Login</a>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>