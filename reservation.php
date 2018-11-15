<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Rezervace</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="style.css">
    <script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
    <script defer src="fontAwesome/fontawesome-all.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<nav>
    <ul>
        <li><a href="index.php">Úvod</a>
        </li><li><a>Fotogalerie</a>
        </li><li><a href="reservation.php" class="active">Rezervace</a>
        </li><li><a>Kontakt</a>
        </li>

    </ul>
</nav>
<div class="wrapper">
    <main id="content">
        <div class="formWrapper">
            <div class="formSteps">
                <ul>
                    <li><a href="#"><i class="fas fa-times-circle fa-fw" id="firstStep"></i><br>Počet osob</a></li>
                    <li><a href="#" class="empty"><i class="fas fa-times-circle fa-fw" id="secondStep"></i><br>Apartmán</a></li>
                    <li><a href="#" class="empty"><i class="fas fa-times-circle fa-fw" id="thirdStep"></i><br>Výběr dnů</a></li>
                    <li><a href="#" class="empty"><i class="fas fa-check-circle fa-fw" id="fourthStep"></i><br>Možnosti</a></li>
                    <li><a href="#" class="empty"><i class="fas fa-times-circle fa-fw" id="fifthStep"></i><br>Osobní údaje</a></li>
                </ul>
            </div>
            <div id="reservation">
                <form id="reservationForm" method="post" autocomplete="off">
                    <div class="tab" id="personTab">
                        <label for="pocetOsob">Počet osob: </label>
                        <!--<input placeholder="Počet osob" name="pocetOsob" id="pocetOsob" type="number" min="1" max="4">-->
                        <select name="pocetOsob" id="pocetOsob">
                            <option value="0">Nevybráno</option>
                            <option value="1">1 osoba</option>
                            <option value="2">2 osoby</option>
                            <option value="3">3 osoby</option>
                            <option value="4">4 osoby</option>
                        </select>
                        <small>Poznámka: Děti do 6 let se nepočítají do obsazení apartmánu.</small>
                        <div class="formNav">
                            <button class="nextButton">Pokračovat</button>
                        </div>
                    </div>

                    <div class="tab" id="listRoomsTab">
                        <label>Dostupné pokoje: </label>


                        <div class="formNav">
                            <button class="backButton">Zpět</button>
                            <button class="nextButton">Pokračovat</button>
                        </div>
                    </div>

                    <div class="tab" id="calendarTab">
                        <label>Přehled volných dnů: </label>
                        <small>Poznámka: Je možné vybrat interval od dvou do patnácti dnů, tedy jedné až čtrnácti nocí.</small>
                        <br>
                        <button id="previousMonth"><i class="fas fa-chevron-left"></i></button>
                        <button id="removeSelected">Odstranit výběr</button>
                        <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>

                        <div class="formNav">
                            <button class="backButton">Zpět</button>
                            <button class="nextButton">Pokračovat</button>
                        </div>
                    </div>

                    <div class="tab" id="additionsTab">
                        <label>Doplňkové možnosti: </label>
                        <input type="checkbox" name="detskaPostel" id="detskaPostel" value="detskaPostel"><label for="detskaPostel">Dětská postýlka</label><br>
                        <input type="checkbox" name="detskaZidle" id="detskaZidle" value="detskaZidle"><label for="detskaZidle">Dětská židle</label><br>
                        <small>Poznámka: ke každé rezervaci je účtována povinná jednorázová platba za úklid v hodnotě 750 Kč.</small>

                        <div class="formNav">
                            <button class="backButton">Zpět</button>
                            <button class="nextButton">Pokračovat</button>
                        </div>
                    </div>

                    <div class="tab" id="infoTab">
                        <label for="jmeno">Jméno: </label>
                        <input placeholder="Jméno" name="jmeno" id="jmeno" type="text" required pattern="\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}">
                        <label for="prijmeni">Příjmení: </label>
                        <input placeholder="Příjmení" name="prijmeni" id="prijmeni" type="text" required pattern="\p{Lu}\p{Ll}{1,25} ?-?\p{L}{1,25}">
                        <label for="telefon">Telefonní číslo:</label>
                        <input placeholder="Telefonní číslo" name="telefon" id="telefon" type="text" required pattern="\+?\d\ {3,30}">
                        <label for="email">Email: </label>
                        <input placeholder="Email" name="email" id="email" type="email" required>
                        <small>
                            Odesláním tohoto formuláře dávate souhlas se zpracováním vašich osobních údajů pouze pro účely rezervace a případné komunikace s vámi.
                        </small>
                        <input placeholder="" name="idApartman" id="idApartman" readonly pattern="">
                        <input placeholder="" name="prijezd" id="prijezd" readonly pattern='\"?\d{4}-\d{2}-\d{2}\"?'>
                        <input placeholder="" name="odjezd" id="odjezd" readonly pattern='\"?\d{4}-\d{2}-\d{2}\"?'>

                        <div class="formNav">
                            <button class="backButton">Zpět</button>
                            <input type="submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<script src="reservation.js"></script>
<script src="calendar.js"></script>
</body>
</html>
