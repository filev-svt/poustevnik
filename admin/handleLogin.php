<?php
// Always start this first
session_start();

if (!empty($_POST)) {
    if (isset($_POST["loginUsername"]) && isset($_POST["loginPassword"])) {

        if (!empty($_POST["loginUsername"]) and !empty($_POST["loginUsername"])) {
            require_once "../database.php";

            $mysql = getDBConnection();

            try {
                $statementLogin = $mysql->prepare("SELECT id_personal, jmeno_personal, prijmeni_personal, heslo, administrator FROM personal WHERE username = ?");
                $statementLogin->execute(array($_POST["loginUsername"]));
                $user = $statementLogin->fetch();
            } catch (Exception $e) {
                echo $e->getCode() . "<br>" . $e->getMessage();
                $user = null;
            }


            if (password_verify($_POST['loginPassword'], $user[3])) {
                $_SESSION["id"] = $user[0];
                $_SESSION["jmeno"] = $user[1];
                $_SESSION["prijmeni"] = $user[2];
                $_SESSION["admin"] = $user["administrator"];

                header("Location: http://poustevnik-apartments.cz/admin/index.php");
                exit();
            } else {
                echo "Špatné uživatelské jméno nebo heslo";
                exit();
            }
        } else {
            echo "Špatné uživatelské jméno nebo heslo";
        }
    }
}
?>