<?php
require "sessionAdminCheck.php";
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
                <a href="index.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li>
                <a href="reservations.php"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
            <li>
                <a href="documents.php" class="active"><i class="far fa-clipboard fa-fw"></i> Dokumenty</a></li>
            <li>
                <a href="personal.php"><i class="far fa-address-book fa-fw"></i> Personál</a></li>
            <li>
                <a href="accomodations.php"><i class="fas fa-home fa-fw"></i> Přehled apartmánů</a></li>

        </ul>
    </nav>


    <main>

        <h2>Administrativa</h2>

        <button class="accordion">Export ve formátu CSV</button>
        <div class="panel">
            <form action="downloadCSV.php" method="get">
                <select name="seznamObdobi">
                    <?php
                    require_once "../database.php";
                    $mysql = getDBConnection();
                    $statement = $mysql->prepare("SELECT DISTINCT DATE_FORMAT(datum_prijezd,'%m-%Y') FROM rezervace ORDER BY datum_prijezd");
                    $statement->execute();
                    $results = $statement->fetchAll();
                    foreach ($results as $result) {
                        echo "<option value='$result[0]'>$result[0]</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Stáhnout">
            </form>
        </div>
    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
</body>
</html>
