<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 15.03.18
 * Time: 1:19
 */

require_once "database.php";

$mysql = getDBConnection();


$jmeno = filter_input(INPUT_POST, "jmeno", FILTER_SANITIZE_STRING);
$prijmeni = filter_input(INPUT_POST, "prijmeni", FILTER_SANITIZE_STRING);
$telefon = filter_input(INPUT_POST, "telefon", FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$pocetOsob = filter_input(INPUT_POST, "pocetOsob", FILTER_SANITIZE_NUMBER_INT);
$datumPrijezd = filter_input(INPUT_POST, "prijezd",  FILTER_SANITIZE_STRING);
$datumOdjezd = filter_input(INPUT_POST, "odjezd",  FILTER_SANITIZE_STRING);
$jednotka = filter_input(INPUT_POST, "idApartman", FILTER_SANITIZE_NUMBER_INT);
$detskaZidle = filter_input(INPUT_POST, "detskaZidle", FILTER_SANITIZE_STRING);
$detskaPostel = filter_input(INPUT_POST, "detskaPostel", FILTER_SANITIZE_STRING);
$token = substr(md5(time()), 0,10);

$soucetCen = array();



if (!isset($jmeno) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/u", $jmeno) or empty($jmeno)) {
    header('HTTP/1.1 500 Internal Server Error');

    die('Chybně vyplněné jméno');
}

if (!isset($prijmeni) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/u", $prijmeni) or empty($prijmeni)) {
    header('HTTP/1.1 500 Internal Server Error');

    die('Chybně vyplněné příjmení');
}

if (!isset($email) or !filter_var($email, FILTER_VALIDATE_EMAIL) or empty($email)) {
    header('HTTP/1.1 500 Internal Server Error');

    die('Chybně vyplněný email');
}

if (!isset($datumPrijezd) or !preg_match("/\d{4}-\d{2}-\d{2}/u", $datumPrijezd) or empty($datumPrijezd)) {
    header('HTTP/1.1 500 Internal Server Error');

    die('Chybné datum příjezdu');
}

if (!isset($datumOdjezd) or !preg_match("/\d{4}-\d{2}-\d{2}/u", $datumOdjezd) or empty($datumOdjezd)) {
    header('HTTP/1.1 500 Internal Server Error');

    die('Chybné datum odjezdu');
}


//první a poslední den
$prvni = new DateTime($datumPrijezd);
$posledni = new DateTime($datumOdjezd);

try {
    if (isset($detskaPostel) && !empty($detskaPostel)) {
        $stPostel = $mysql->prepare("SELECT cena FROM poplatek WHERE id_poplatek = 2");
        $stPostel->execute();
        $rsPostel = $stPostel->fetch();
        $rsPostel = $rsPostel[0];
    } else {
        $rsPostel = null;
    }

    if (isset($detskaZidle) && !empty($detskaZidle)) {
        $stZidle = $mysql->prepare("SELECT cena FROM poplatek WHERE id_poplatek = 3");
        $stZidle->execute();
        $rsZidle = $stZidle->fetch();
        $rsZidle = $rsZidle[0];
    } else {
        $rsZidle = null;
    }


    while ($prvni < $posledni) {
        if(!is_null($rsPostel)){
            array_push($soucetCen, (int)$rsPostel);
        }
        if(!is_null($rsZidle)){
            array_push($soucetCen, (int)$rsZidle);
        }

        $statementPrice = $mysql->prepare("SELECT cena FROM cenova_sazba WHERE jednotka_id_jednotka = ? AND DATE_FORMAT(? ,'%m-%d') BETWEEN DATE_FORMAT(datum_od, '%m-%d') AND DATE_FORMAT(datum_do, '%m-%d')");

        $den = $prvni->format("Y-m-d");

        $statementPrice->execute(array($jednotka, $den));

        $denniCena = $statementPrice->fetchAll();

        array_push($soucetCen,(int)$denniCena[0][0]);

        $prvni->modify("+1 day");
    }

    $stUklid = $mysql->prepare("SELECT cena FROM poplatek WHERE id_poplatek = 1");
    $stUklid->execute();
    $rsUklid = $stUklid->fetch();
    $rsUklid = $rsUklid[0];


    array_push($soucetCen, $rsUklid);


    $cena = array_sum($soucetCen);


    $statement = $mysql->prepare("INSERT INTO rezervace(jmeno, prijmeni, telefon, email, pocet_osob, datum_prijezd, datum_odjezd, celkova_cena, token, jednotka_id_jednotka, jednotka_zarizeni_id_zarizeni) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $statement->bindValue(1,$jmeno,PDO::PARAM_STR);
    $statement->bindValue(2,$prijmeni,PDO::PARAM_STR);
    $statement->bindValue(3,$telefon,PDO::PARAM_STR);
    $statement->bindValue(4,$email,PDO::PARAM_STR);
    $statement->bindValue(5,$pocetOsob,PDO::PARAM_INT);
    $statement->bindValue(6,$datumPrijezd,PDO::PARAM_STR);
    $statement->bindValue(7,$datumOdjezd,PDO::PARAM_STR);
    $statement->bindValue(8,$cena,PDO::PARAM_INT);
    $statement->bindValue(9,$token,PDO::PARAM_STR);
    $statement->bindValue(10,$jednotka, PDO::PARAM_INT);
    $statement->bindValue(11,1,PDO::PARAM_INT);

    $statement->execute();



    echo "Rezervace úspěšně vytvořena. Předpokládaná cena bez doplňkových poplatků $cena Kč.";


    define("ODESILATEL", "Apartmány Poustevník <info@poustevnik-apartments.cz>");
    $prijemce = $email;
    $predmet  = "Rezervace $token";
    $zprava   = "Vaše rezervace na jméno $jmeno $prijmeni byla zpracována. Platba v hodnotě $cena Kč bude účtována 
        na začátku vašeho pobytu. Detaily rezervace: <br>
        Kód: $token<br>
        Počet osob: $pocetOsob,<br>
        Datum příjezdu: ".$prvni->format("j.n.Y")."<br>
        Datum odjezdu: ".$posledni->format("j.n.Y").".<br>
        Těšíme se na vaši návštěvu.";
    $hlavicky = [
        "MIME-Version: 1.0",
        "Content-type: text/html; charset=utf-8",
        "From: ".ODESILATEL,
        "Reply-To: ".ODESILATEL,
        'X-Mailer: PHP/'.phpversion()
    ];
    $hlavicky = implode("\r\n", $hlavicky);
    if (mail($prijemce, $predmet, $zprava, $hlavicky)){
        echo "<br>V emailu vám budou zaslány detaily o rezervaci";
    }else{
        echo "<br>Chyba při zpracování emailu, kontaktujte prosím podporu";
    }

} catch (PDOException $exception) {

    header('HTTP/1.1 500 Internal Server Error');

    die("Chyba: " . $exception->getCode()."<br>".$exception->getMessage());

}
