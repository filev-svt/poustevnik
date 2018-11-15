<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 02.04.18
 * Time: 16:17
 */
session_start();

if (!isset( $_SESSION["id"])) {
    header("Location: login.php");
} else {
    if ($_SESSION["admin"] == false) {
        echo "nedostatečná oprávnění";
        exit();
    }
}