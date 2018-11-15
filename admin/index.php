<?php
session_start();

if (!isset( $_SESSION["id"])) {
    header("Location: login.php");
}
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Administrace</title>
    <?php
    include "header.php";
    ?>
</head>
<body>

<header>
    <?php
    include "loggedUserInfo.php";
    ?>
</header>

<div class="wrapper">
    <nav>
        <ul>
            <li>
                <a href="index.php" class="active"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li>
                <a href="reservations.php"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
        <?php
        if ($_SESSION["admin"] == true) {
        ?>
            <li>
                <a href="documents.php"><i class="far fa-clipboard fa-fw"></i> Dokumenty</a></li>
            <li>
                <a href="personal.php"><i class="far fa-address-book fa-fw"></i> Personál</a></li>
            <li>
                <a href="accomodations.php"><i class="fas fa-home fa-fw"></i> Přehled apartmánů</a></li>
        <?php
        }
        ?>
        </ul>
    </nav>


    <main>

        <h2>Dashboard</h2>

        <div id="dashboardContent">
            <?php
            include "dashItems.php";
            generateDash();
            ?>
        </div>
    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
</body>
</html>
