<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 27.02.18
 * Time: 11:52
 */

function resHistory() {
    require_once "../database.php";

    $mysql = getDBConnection();

    $headers = array("Odkaz","Pokoj", "Jméno", "Příjmení", "Příjezd", "Odjezd");

    $today = new DateTime();

    $statementSeznam = $mysql->prepare("SELECT id_rezervace, nazev_jednotka, jmeno, prijmeni, datum_prijezd, datum_odjezd  
FROM rezervace JOIN jednotka ON jednotka_id_jednotka = jednotka.id_jednotka WHERE datum_prijezd < ?");

    $statementSeznam->execute(array($today->format("Y-m-d")));

    $seznamResult = $statementSeznam->fetchAll();

    $list = "<table class='resList'>";

    foreach ($headers as $header) {
        $list .= "<th class='headers'>$header</th>";
    }

    foreach ($seznamResult as $item) {
        $list .= "<tr>";

        for ($i = 0; $i < $statementSeznam->columnCount(); $i++) {
            if ($i == 0) {
                $list .= "<td><a href='detail.php?id=$item[$i]'>Detail</a></td>";
            } else if ($i == 4 or $i == 5) {
                $date = new DateTime($item[$i]);
                $list .= "<td>".$date->format("d.m.Y")."</td>";
            } else {
                $list .= "<td>$item[$i]</td>";
            }
        }

        $list .= "</tr>";
    }

    $list .= "</table>";


    echo $list;


}