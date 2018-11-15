<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 28.02.18
 * Time: 17:57
 */

function accomodationList() {
    require_once "../database.php";

    $mysql = getDBConnection();
    try {
        $statementAccommodation = $mysql->prepare("SELECT nazev_jednotka, typ_jednotka, maximalni_obsazeni, popis_jednotka, id_jednotka FROM jednotka");

        $statementAccommodation->execute();

        $results = $statementAccommodation->fetchAll();
    } catch (PDOException $exception) {
        echo $exception->getMessage();
    }


    $accomodation = "";

    $description = array("", "Typ apartmánu: ", "Maximální obsazení: ", "Popis: ");

    foreach ($results as $result) {
        $accomodation .= "<button class='accordion'>{$result['nazev_jednotka']}</button>";
        $accomodation .= "<div class='panel'>";
        $accomodation .= "<table class='accomodationTable'>";
        $accomodation .= "<h3>Detaily</h3>";

        for ($i = 0; $i < $statementAccommodation->columnCount()-1; $i++) {

            $accomodation .= "<tr>";
            if ($i == 0) {
                continue;
            }
            if ($i == 3) {
                $accomodation .= "<td colspan='1'>$description[$i]</td>";
                $accomodation .= "<td colspan='3'>$result[$i]</td>";
            } else {
                $accomodation .= "<td colspan='1'>$description[$i]</td>";
                $accomodation .= "<td colspan='3'>$result[$i]</td>";

            }
            $accomodation .= "</tr>";

        }

        $accomodation .= "</table>";




        //tabulka cen
        try {
            $statementPrices = $mysql->prepare("SELECT id_cenova_sazba, nazev_sazba, cena, datum_od, datum_do, jednotka_id_jednotka FROM cenova_sazba WHERE jednotka_id_jednotka = ?");

            $statementPrices->execute(array($result['id_jednotka']));

            $prices = $statementPrices->fetchAll();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }


        $accomodation .= "<form class='pricesForm' method='post'>";
        $accomodation .= "<table>";
        $accomodation .= "<h3>Cenové sazby pro $result[0]: </h3>";
        $accomodation .= "<tr>";
        $accomodation .= "<th>Název</th>";
        $accomodation .= "<th>Cena</th>";
        $accomodation .= "<th>Od</th>";
        $accomodation .= "<th>Do</th>";
        $accomodation .= "</tr>";
        foreach ($prices as $price) {
            $accomodation .= "<tr>";
            for ($i = 0; $i < $statementPrices->columnCount(); $i++) {

                switch ($i){
                    case 0:
                        $accomodation .= "<input readonly name='idSazba[]' type='hidden' value='$price[$i]'>";
                        break;
                    case 1:
                        $accomodation .= "<td>$price[$i]</td>";
                        break;
                    case 2:
                        $accomodation .= "<td><input name='cena[]' readonly value='$price[$i]'></td>";
                        break;
                    case 3:
                        $date = new DateTime($price[$i]);
                        $accomodation .= "<td><input name='datumOd[]' readonly value='".$date->format("d.m.")."'</td>";
                        break;
                    case 4:
                        $date = new DateTime($price[$i]);
                        $accomodation .= "<td><input name='datumDo[]' readonly value='".$date->format("d.m.")."'</td>";
                        break;
                    case 5:
                        $accomodation .= "<input readonly name='idApartman[]' type='hidden' value='$price[$i]'>";
                        break;
                    default:
                        $accomodation .= "<td>$price[$i]</td>";
                        break;
                }
            }

            $accomodation .= "</tr>";
        }
        $accomodation .= "</table>";
        $accomodation .= "<button class='pricesEdit'>Upravit sazby</button>";
        $accomodation .= "<input type='submit' value='Potvrdit změny'>";
        $accomodation .= "</form>";
        $accomodation .= "</div>";

    }

    echo $accomodation;
}