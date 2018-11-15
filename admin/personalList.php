<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 27.02.18
 * Time: 19:24
 */

function personalList() {

    require_once "../database.php";

    $mysql = getDBConnection();


    $headers = array("ID", "Jméno", "Příjmení", "Username", "Status", "Email");

    $statementPersonal = $mysql->prepare("SELECT id_personal, jmeno_personal, prijmeni_personal, username, administrator, email FROM personal");

    $statementPersonal->execute();

    $resultsPersonal = $statementPersonal->fetchAll();

    $personalList = "<table>";

    foreach ($headers as $header) {
        $personalList .= "<th class='headers'>$header</th>";
    }
    foreach ($resultsPersonal as $resultPersonal) {
        $personalList .= "<tr>";

        for ($i = 0; $i < $statementPersonal->columnCount(); $i++) {
            if ($i == 4) {
                if ($resultPersonal[$i] == true) {
                    $personalList .= "<td>Administrátor</td>";
                } else {
                    $personalList .= "<td>Personál</td>";
                }
            } else {
                $personalList .= "<td>$resultPersonal[$i]</td>";
            }
        }

        $personalList .= "</tr>";
    }

    $personalList .= "</table>";

    echo $personalList;
}