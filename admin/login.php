<?php
session_start();

if (isset($_SESSION["id"])) {
    header("Location: http://localhost:8888/bakalarka/admin/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrace</title>
    <link type="text/css" rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <script
        src="http://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
    <script defer src="../fontAwesome/fontawesome-all.js"></script>
</head>
<body>

<header>
    <h1>Apartmány Poustevník, Pec pod Sněžkou</h1>
</header>
<form class="loginForm" method="post" action="handleLogin.php">
    <input type="text" name="loginUsername" placeholder="Uživatelské jméno">
    <input type="password" name="loginPassword" placeholder="Heslo">
    <input type="submit">
</form>
<script type="text/javascript" src="accordion.js"></script>
</body>
</html>

