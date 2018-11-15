<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 27.02.18
 * Time: 19:00
 */

require_once "../database.php";

$mysql = getDBConnection();


$jmenoAdmin = filter_input(INPUT_POST, "jmenoAdmin",FILTER_SANITIZE_STRING);
$prijmeniAdmin = $_POST["prijmeniAdmin"];
$emailAdmin = $_POST["emailAdmin"];
$usernameAdmin = $_POST["usernameAdmin"];
$passwordAdmin = $_POST["passwordAdmin"];



if (!isset($jmenoAdmin) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/", $jmenoAdmin) or empty($jmenoAdmin)) {
    echo "Chybně vyplněné jméno";
    exit();
}

if (!isset($prijmeniAdmin) or !preg_match("/\p{Lu}\p{Ll}{1,20} ?-?\p{L}{1,20}/", $prijmeniAdmin) or empty($prijmeniAdmin)) {
    echo "Chybně vyplněné příjmení";
    exit();
}

if (!isset($emailAdmin) or !filter_var($emailAdmin, FILTER_VALIDATE_EMAIL) or empty($emailAdmin)) {
    echo "Chybně vyplněný email";
    exit();
}

if (!isset($usernameAdmin) or !preg_match("/^(?:[a-zA-Z0-9_\.-]){4,50}/u",$usernameAdmin) or empty($usernameAdmin)) {
    echo "Chybně vyplněné uživatelské jméno";
    exit();
}

if (!isset($passwordAdmin) or !preg_match("/^[\W\w]{8,100}/u", $passwordAdmin) or empty($passwordAdmin)) {
    echo "Neodpovídající heslo";
    exit();
}

$hashedPassword = password_hash($passwordAdmin, PASSWORD_BCRYPT);



try {
    $statement = $mysql->prepare("INSERT INTO personal(jmeno_personal, prijmeni_personal, username, heslo, administrator, email) VALUES (?, ?, ?, ?, ?, ?)");

    $statement->execute(array($jmenoAdmin, $prijmeniAdmin, $usernameAdmin, $hashedPassword, true, $emailAdmin,));

    echo "Úspěšně vytvořen účet administrátora";

} catch (PDOException $exception) {

    echo "Chyba: " . $exception->getCode();

}






