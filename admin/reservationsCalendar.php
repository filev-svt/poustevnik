<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 21.02.18
 * Time: 14:21
 */


function adminCalendar() {

    require_once "../database.php";

    $mysql = getDBConnection();

    $statementJednotka = $mysql->query("SELECT * FROM jednotka");


    $resultsJednotka = $statementJednotka->fetchAll();

    $day = new DateTime("", new DateTimeZone("Europe/Prague"));


    $adminCalendar = "<table class='timeline'>";
    $adminCalendar .= "<th class='headers'>Ubytovací jednotka</th>";

    $daysList = array();

    for ($i = 0; $i < 7; $i++) {
        $adminCalendar .= "<th class='headers'>".$day->format("j.n.")."</th>";

        array_push($daysList,$day->format("Y-m-d"));

        $day->modify("+1 day");
    }

    foreach ($resultsJednotka as $resultJednotka) {
        $nazev = $resultJednotka['nazev_jednotka'];
        $cisloJednotky = $resultJednotka['id_jednotka'];

        $adminCalendar .= "<tr>";
        $adminCalendar .= "<td><b>$nazev</b></td>";


        foreach ($daysList as $dayItem) {

            $statementAvailability = $mysql->prepare("SELECT jmeno, prijmeni, nazev_jednotka FROM rezervace 
                JOIN jednotka ON rezervace.jednotka_id_jednotka = jednotka.id_jednotka
                WHERE id_jednotka = ? AND ? BETWEEN datum_prijezd AND datum_odjezd");


            $statementAvailability->execute(array($cisloJednotky,$dayItem));


            $resultsAvailability = $statementAvailability->fetch();

            if ($statementAvailability->rowCount() > 0){
                $adminCalendar .= "<td class='taken'>$resultsAvailability[0]"." "."$resultsAvailability[1]</td>";
            } else {
                $adminCalendar .= "<td>Volno</td>";
            }
        }



        $adminCalendar .= "</tr>";

    }
    $adminCalendar .= "</table>";

    echo $adminCalendar;
}
