<?php
session_start();

if (!isset( $_SESSION["id"])) {
    header("Location: login.php");
}
if (isset($_POST["id"]) and isset($_POST["token"])) {
    require_once "../database.php";
    $mysql = getDBConnection();
    try {
        $statement = $mysql->prepare("DELETE FROM rezervace WHERE id_rezervace = ? AND token = ?");
        $statement->execute(array($_POST["id"], $_POST["token"]));

        $result = "Rezervace ".$_POST["token"]." úspěšně zrušena.";
    } catch (PDOException $e) {
        echo $e->getCode()."<br>";
    }

} else {
    header("Location: index.php");
}
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Detail rezervace </title>
    <?php
    include "header.php";
    ?>
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
                <a href="index.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li>
                <a href="reservations.php" class="active"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
            <?php
            if ($_SESSION["admin"] == true) {
                ?>
                <li>
                    <a href="documents.php"><i class="far fa-clipboard fa-fw"></i> Administrativa</a></li>
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

        <h2><?=$result?></h2>


    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
</body>
</html>
