<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 08.03.18
 * Time: 23:29
 */

require_once "database.php";
$mysql = getDBConnection();

$persons = filter_input(INPUT_GET, "pocetOsob", FILTER_SANITIZE_NUMBER_INT);

try {
    $statementRooms = $mysql->prepare("SELECT id_jednotka, nazev_jednotka, typ_jednotka, maximalni_obsazeni, MIN(cena) FROM jednotka JOIN cenova_sazba ON jednotka.id_jednotka = cenova_sazba.jednotka_id_jednotka AND jednotka.zarizeni_id_zarizeni = cenova_sazba.jednotka_zarizeni_id_zarizeni WHERE maximalni_obsazeni >= ?");

    $statementRooms->execute(array($persons));

    $results = $statementRooms->fetchAll();


    $rooms = "<div id='roomList'>";

    foreach ($results as $result) {

        $rooms .= "<button class='roomButton' id='jednotka$result[0]'>";
        $rooms .= "<h3>$result[1]</h3>";
        $rooms .= "<ul>";

        $rooms .= "<li>Typ $result[2]</li>";
        $rooms .= "<li>Maximálně $result[3] osoby</li>";
        $rooms .= "<li>Ceny od $result[4] Kč za noc</li>";

        $rooms .= "</ul>";
        $rooms .= "</button>";
    }

    $rooms .= "</div>";
    echo $rooms;
} catch (Exception $e) {
    echo $e->getCode()."<br>".$e->getMessage();
}
