<?php
session_start();

if (!isset( $_SESSION["id"])) {
    header("Location: login.php");
}
if (isset($_GET["id"])) {
    require_once "../database.php";
    $mysql = getDBConnection();
    try {
        $statement = $mysql->prepare("SELECT * FROM rezervace JOIN jednotka ON rezervace.jednotka_id_jednotka = jednotka.id_jednotka AND rezervace.jednotka_zarizeni_id_zarizeni = jednotka.zarizeni_id_zarizeni WHERE id_rezervace = ?");
        $statement->execute(array($_GET["id"]));

        $result = $statement->fetch();
        if (!$result){
            header("Location: reservations.php");
        }
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

        <h2>Rezervace <?=$result["token"]?></h2>
        <div class="dashItem" id="reservationDetail">
            <table>
                <tr>
                    <td>Jméno:</td>
                    <td><?=$result["jmeno"]?></td>
                </tr>
                <tr>
                    <td>Příjmení:</td>
                    <td><?=$result["prijmeni"]?></td>
                </tr>
                <tr>
                    <td>Telefon:</td>
                    <td><?=$result["telefon"]?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?=$result["email"]?></td>
                </tr>
                <tr>
                    <td>Počet osob:</td>
                    <td><?=$result["pocet_osob"]?></td>
                </tr>
                <tr>
                    <td>Datum příjezdu:</td>
                    <td>
                        <?php
                        $datum = new DateTime($result["datum_prijezd"]);
                        echo $datum->format("d.m.Y")
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Datum odjezdu:</td>
                    <td>
                        <?php
                        $datum = new DateTime($result["datum_odjezd"]);
                        echo $datum->format("d.m.Y")
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Celková cena:</td>
                    <td><?=$result["celkova_cena"]?> Kč</td>
                </tr>
                <tr>
                    <td>Token:</td>
                    <td><?=$result["token"]?></td>
                </tr>
                <tr>
                    <td>Apartmán:</td>
                    <td><?=$result["nazev_jednotka"]?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <form method="post" action="delete.php">
                <input type="hidden" name="id" value="<?=$result["id_rezervace"]?>" readonly>
                <input type="hidden" name="token" value="<?=$result["token"]?>" readonly>
                <input type="submit" id="deleteReservation" value="Smazat rezervaci">
            </form>
        </div>

    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
<script type="text/javascript" src="deleteReservation.js"></script>
</body>
</html>
