<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 27.02.18
 * Time: 19:00
 */

require_once "../database.php";

$mysql = getDBConnection();


$jmenoPersonal = $_POST["jmenoPersonal"];
$prijmeniPersonal = $_POST["prijmeniPersonal"];
$emailPersonal = $_POST["emailPersonal"];
$usernamePersonal = $_POST["usernamePersonal"];
$passwordPersonal = $_POST["passwordPersonal"];



if (!isset($jmenoPersonal) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/", $jmenoPersonal) or empty($jmenoPersonal)) {
    echo "Chybně vyplněné jméno";
    exit();
}

if (!isset($prijmeniPersonal) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/", $prijmeniPersonal) or empty($prijmeniPersonal)) {
    echo "Chybně vyplněné příjmení";
    exit();
}

if (!isset($emailPersonal) or !filter_var($emailPersonal, FILTER_VALIDATE_EMAIL) or empty($emailPersonal)) {
    echo "Chybně vyplněný email";
    exit();
}

if (!isset($usernamePersonal) or !preg_match("/^(?:[a-zA-Z0-9_\.-]){4,50}/u",$usernamePersonal) or empty($usernamePersonal)) {
    echo "Chybně vyplněné uživatelské jméno";
    exit();
}

if (!isset($passwordPersonal) or !preg_match("/^[\W\w]{8,100}/u", $passwordPersonal) or empty($passwordPersonal)) {
    echo "Neodpovídající heslo";
    exit();
}

$hashedPassword = password_hash($passwordPersonal, PASSWORD_BCRYPT);


try {
    $statement = $mysql->prepare("INSERT INTO personal(jmeno_personal, prijmeni_personal, username, heslo, administrator, email) VALUES (?, ?, ?, ?, ?, ?)");

    $statement->execute(array($jmenoPersonal, $prijmeniPersonal, $usernamePersonal, $hashedPassword, false, $emailPersonal,));

    echo "Úspěšně vytvořen účet personálu";

} catch (PDOException $exception) {

    echo "Chyba: ".$exception->getCode()."<br>".$exception->getMessage();

}