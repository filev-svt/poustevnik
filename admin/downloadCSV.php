<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 26.03.18
 * Time: 23:18
 */

$obdobi = filter_input(INPUT_GET, "seznamObdobi", FILTER_SANITIZE_STRING);


require_once "../database.php";

$mysql = getDBConnection();

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=rezervace_".$obdobi.".csv");

$output = fopen("php://output", "w");

fputcsv($output, array("Jméno", "Příjmení", "Telefon", "Email", "Počet osob", "Datum příjezdu", "Datum odjezdu", "Kód rezervace", "Jednotka"));

$statement = $mysql->prepare("SELECT jmeno, prijmeni, telefon, email, pocet_osob, datum_prijezd, datum_odjezd, celkova_cena, token, nazev_jednotka 
FROM rezervace JOIN jednotka ON rezervace.jednotka_id_jednotka = jednotka.id_jednotka AND rezervace.jednotka_zarizeni_id_zarizeni = jednotka.zarizeni_id_zarizeni 
WHERE DATE_FORMAT(datum_prijezd, '%m-%Y') = ?");

$statement->execute(array($obdobi));

$results = $statement->fetchAll();

foreach ($results as $result) {
    fputcsv($output, $result);
}