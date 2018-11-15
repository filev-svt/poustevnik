<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 09.04.18
 * Time: 20:51
 */



function generateDash() {
    require_once "../database.php";
    $mysql = getDBConnection();

    require_once ("../vendor/autoload.php");


    date_default_timezone_set("Europe/Prague");


    $todayDate = new DateTime();


    $dash = "<div class='dashItem'>";
    $dash .= "<h3><i class=\"far fa-envelope fa-fw\"></i> Nové emaily: </h3>";
    $dash .= "<ul>";

    //DODĚLAT
    $dash .= "<li>Žádné nové emaily.</li>";

    $dash .= "</ul>";
    $dash .= "</div>";

    $dash .= "<div class='dashItem'>";
    $dash .= "<h3><i class=\"fas fa-bold fa-fw\"></i> Booking.com: </h3>";
    $dash .= "<ul>";

    //DODĚLAT
    $dash .= "<li>Žádné nové rezervace Booking.com.</li>";

    $dash .= "</ul>";
    $dash .= "</div>";


    $dash .= "<div class='dashItem'>";
    $dash .= "<h3><i class=\"far fa-calendar-plus fa-fw\"></i> Klienti přijíždějící dnes: </h3>";
    $dash .= "<ul>";
    $statement = $mysql->prepare("SELECT jmeno, prijmeni, nazev_jednotka FROM rezervace JOIN jednotka ON rezervace.jednotka_id_jednotka = jednotka.id_jednotka AND rezervace.jednotka_zarizeni_id_zarizeni = jednotka.zarizeni_id_zarizeni WHERE datum_prijezd = ? ");
    $statement->execute(array($todayDate->format("Y-m-d")));
    $results = $statement->fetchAll();
    if ($statement->rowCount() < 1) {
        $dash .= "<li>Dnes nepřijíždí žádný klient.</li>";
    } else {
        foreach ($results as $result) {
            $dash .= "<li>$result[0] $result[1], $result[2]</li>";
        }
    }
    $dash .= "</ul>";
    $dash .= "</div>";

    $dash .= "<div class='dashItem'>";
    $dash .= "<h3><i class=\"far fa-calendar-minus fa-fw\"></i> Klienti odjíždějící dnes: </h3>";
    $dash .= "<ul>";
    $statement = $mysql->prepare("SELECT jmeno, prijmeni, nazev_jednotka FROM rezervace JOIN jednotka ON rezervace.jednotka_id_jednotka = jednotka.id_jednotka AND rezervace.jednotka_zarizeni_id_zarizeni = jednotka.zarizeni_id_zarizeni WHERE datum_odjezd = ?");
    $statement->execute(array($todayDate->format("Y-m-d")));
    $results = $statement->fetchAll();
    if ($statement->rowCount() < 1) {
        $dash .= "<li>Dnes neodjíždí žádný klient.</li>";
    } else {
        foreach ($results as $result) {
            $dash .= "<li>$result[0] $result[1], $result[2]</li>";
        }
    }
    $dash .= "</ul>";
    $dash .= "</div>";


    $dash .= "<div class='dashItem' id='weather'>";
    $dash .= '<iframe id="forecast_embed" frameborder="0" height="245" width="100%" src="//forecast.io/embed/#lat=50.694845&lon=15.735077&name=Pec pod Sněžkou&color=#4DB6AC&units=ca"></iframe>';
    $dash .= "</div>";
    echo $dash;
}
