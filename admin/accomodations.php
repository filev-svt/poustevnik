<?php
require "sessionAdminCheck.php";

if (isset ($_POST["idSazba"],$_POST["datumOd"],$_POST["datumDo"],$_POST["cena"],$_POST["idApartman"]) and !empty(array($_POST["idSazba"],$_POST["datumOd"],$_POST["datumDo"],$_POST["cena"],$_POST["idApartman"]))) {
    require "sessionAdminCheck.php";

    require_once "../database.php";
    $mysql = getDBConnection();


    for ($i = 0; $i < count($_POST["idSazba"]); $i++) {
        try {
            $datumOd = new DateTime($_POST["datumOd"][$i] . "0000");
            $datumDo = new DateTime($_POST["datumDo"][$i] . "0000");

            $statement = $mysql->prepare("UPDATE cenova_sazba SET cena = ?, datum_od = ?, datum_do = ? WHERE id_cenova_sazba = ? AND jednotka_id_jednotka = ?");
            $statement->execute(array($_POST["cena"][$i], $datumOd->format("Y-m-d"), $datumDo->format("Y-m-d"), $_POST["idSazba"][$i]));
        } catch (PDOException $exception) {

        }
    }
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
                <a href="index.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li>
                <a href="reservations.php"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
            <li>
                <a href="documents.php"><i class="far fa-clipboard fa-fw"></i> Dokumenty</a></li>
            <li>
                <a href="personal.php"><i class="far fa-address-book fa-fw"></i> Personál</a></li>
            <li>
                <a href="accomodations.php" class="active"><i class="fas fa-home fa-fw"></i> Přehled apartmánů</a></li>
        </ul>
    </nav>



    <main>

        <h2>Přehled apartmánů</h2>

        <?php
        include "accomodationList.php";
        accomodationList();
        ?>
    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
<script type="text/javascript" src="accomodationEdit.js"></script>
</body>
</html>
