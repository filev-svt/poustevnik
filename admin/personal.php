<?php
require "sessionAdminCheck.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Administrace</title>
    <?php
    include "header.php";
    ?>
</head>
<body>

<header>
    <?php
    include "loggedUserInfo.php";
    ?>
</header>

<div class="wrapper">
    <nav>
        <ul>
            <li>
                <a href="index.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li>
                <a href="reservations.php"><i class="far fa-calendar-alt fa-fw"></i> Rezervace</a></li>
            <li>
                <a href="documents.php"><i class="far fa-clipboard fa-fw"></i> Administrativa</a></li>
            <li>
                <a href="personal.php" class="active"><i class="far fa-address-book fa-fw"></i> Personál</a></li>
            <li>
                <a href="accomodations.php"><i class="fas fa-home fa-fw"></i> Přehled apartmánů</a></li>

        </ul>
    </nav>



    <main>

        <h2>Personál</h2>

        <button class="accordion">Přehled personálu</button>
        <div class="panel" id="personalListWrapper">
            <?php
            include "personalList.php";
            personalList();
            ?>
        </div>
        <button class="accordion">Nový administrátor</button>
        <div class="panel" id="formAdminWrapper">
            <form id="formAdmin" action="submitAdmin.php" method="post" autocomplete="off">
                <input placeholder="Jméno" name="jmenoAdmin" type="text" pattern='\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}' required>
                <input placeholder="Příjmení" name="prijmeniAdmin" type="text" pattern='\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}' required>
                <input placeholder="Email" name="emailAdmin" type="email" required>
                <input placeholder="Username" name="usernameAdmin" type="text" required>
                <input placeholder="Heslo" name="passwordAdmin" type="password" required>
                <input type="submit" value="Přidat">
            </form>
        </div>
        <button class="accordion">Nový personál</button>
        <div class="panel" id="formPersonalWrapper">
            <form id="formPersonal" action="submitPersonal.php" method="post" autocomplete="off">
                <input placeholder="Jméno" name="jmenoPersonal" type="text" pattern='\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}' required>
                <input placeholder="Příjmení" name="prijmeniPersonal" type="text" pattern='\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}' required>
                <input placeholder="Email" name="emailPersonal" type="email" required>
                <input placeholder="Username" name="usernamePersonal" type="text" required>
                <input placeholder="Heslo" name="passwordPersonal" type="password" required>
                <input type="submit" value="Přidat">
            </form>
        </div>
    </main>
</div>
<script type="text/javascript" src="mobile.js"></script>
<script type="text/javascript" src="accordion.js"></script>
<script type="text/javascript" src="submitPersonal.js"></script>
</body>
</html>
