<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 06.04.18
 * Time: 12:47
 */

$header = "<a class='mobileNav' href='#'><i class='fas fa-bars fa-fw'></i></a>";
$header .= "<h1><a href='index.php'>Apartmány Poustevník</a></h1>";
$header .= "<a class='userLogo' href='#'><i class='far fa-user fa-fw'></i></a>";
$header .= "<div class='headerRight'>";
$header .= "<ul>";
$header .= "<li>".htmlspecialchars($_SESSION["jmeno"])." ".htmlspecialchars($_SESSION["prijmeni"])."</li>";
if ($_SESSION["admin"] == true) {
    $header .=  "<li>Status: Admin.</li>";
} else {
    $header .=  "<li>Status: Personál.</li>";
}
$header .= "<li><a href='handleLogout.php'><i class=\"fas fa-sign-out-alt fa-fw\"></i> Odhlásit se</a></li>";
$header .= "</ul>";
$header .= "</div>";

echo $header;