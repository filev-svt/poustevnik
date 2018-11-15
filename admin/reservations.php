<?php
session_start();

if (!isset( $_SESSION["id"])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
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
                <a href="reservations.php" class="active"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
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

        <h2>Rezervace</h2>

        <button class="accordion">Přehled 7 dní</button>
        <div class="panel">
            <?php
            include "reservationsCalendar.php";
            adminCalendar();
            ?>
        </div>
        <button class="accordion">Nadcházející rezervace</button>
        <div class="panel">
            <?php
            include "reservationsList.php";
            resList();
            ?>
        </div>
        <button class="accordion">Historie rezervací</button>
        <div class="panel">
            <?php
            include "reservationsHistory.php";
            resHistory();
            ?>
        </div>

        <?php
        require_once "../database.php";

        $mysql = getDBConnection();

        $statementForms = $mysql->prepare("SELECT id_jednotka, nazev_jednotka, maximalni_obsazeni FROM jednotka");
        $statementForms->execute();

        $forms = $statementForms->fetchAll();

        require_once "../calendar.php";

        foreach ($forms as $form) {
            $resForm = "<button class='accordion'>";
            $resForm .= "Nová rezervace - $form[1]";
            $resForm .= "</button>";
            $resForm .= "<div class='panel'>";
            $resForm .= "<button id='previousMonth'><i class='fas fa-chevron-left'></i></button>";
            $resForm .= "<button id='removeSelected'>Odstranit výběr</button>";
            $resForm .= "<button id='nextMonth'><i class='fas fa-chevron-right'></i></button>";
            $resForm .= "<form class='adminRes' action='../createReservation.php' method='post'>";
            echo $resForm;
            generateYear($form[0]);

            $resForm = "<label for='pocetOsob'>Počet osob:</label>";
            $resForm .= "<input placeholder='Počet osob' name='pocetOsob' id='pocetOsob' type='number' min='1' max=$form[2] required>";
            $resForm .= "<label for='jmeno'>Jméno:</label>";
            $resForm .= "<input placeholder='Jméno' name='jmeno' type='text' pattern='\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}' required>";
            $resForm .= "<label for='prijmeni'>Příjmení:</label>";
            $resForm .= "<input placeholder='Příjmení' name='prijmeni' type='text' pattern='\p{Lu}\p{Ll}{0,25} ?-?\p{L}{1,25}' required>";
            $resForm .= "<label for='telefon'>Telefonní číslo::</label>";
            $resForm .= "<input placeholder='Telefonní číslo' name='telefon' type='text' pattern='\+?\d\ {3,30}' required>";
            $resForm .= "<label for='email'>Email:</label>";
            $resForm .= "<input placeholder='Email' name='email' type='email' required>";
            $resForm .= "<label for=\"detskaPostel\">Dětská postýlka</label><input type=\"checkbox\" name=\"detskaPostel\" id=\"detskaPostel\" value=\"detskaPostel\">";
            $resForm .= "<label for=\"detskaZidle\">Dětská židle</label><input type=\"checkbox\" name=\"detskaZidle\" id=\"detskaZidle\" value=\"detskaZidle\">";

            $resForm .= "<input name='idApartman' id='idApartman' readonly value='$form[0]' required type='hidden'>";
            $resForm .= "<input name='prijezd' id='prijezd' pattern='^\d{4}-\d{2}-\d{2}' required readonly type='hidden'>";
            $resForm .= "<input name='odjezd' id='odjezd' pattern='^\d{4}-\d{2}-\d{2}' required readonly type='hidden'>";
            $resForm .= "<input type='submit'>";
            $resForm .= "</form>";
            $resForm .= "</div>";
            echo $resForm;
        }
        ?>
    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="accordion.js"></script>
</body>
</html>
