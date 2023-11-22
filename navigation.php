<?php
    session_start();
?>
<nav class="navbar navbar-expand-sm navbar-light bg-info p-2 mb-5">
    <div class="container-fluid">
        <a class="navbar-brand d-block mx-auto text-center" href="index.php"> Game-O-Pedia
            <img src="./logo.png" alt="Logo" style="width:80px;" class="rounded-pill">
        </a>


        <div class="collapse navbar-collapse m-3" id="mynavbar">

            <ul class="navbar-nav ms-auto px-4">
                <?php if (isset($_SESSION['logged_in'])): ?>
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