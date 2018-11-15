<?php
/**
 * Created by PhpStorm.
 * User: sf
 * Date: 06.02.18
 * Time: 18:01
 */

function generate($jarmila) {
    $date = new DateTime();

    for ($i = 0; $i < 12; $i++) {

        createCalendar($date->format("Y"), $date->format("n"), $jarmila, $i);

        $date->modify("+1 month");

    }
}



function createCalendar($year, $month, $apartman, $iteration) {

    require_once "../database.php";

    $mysql = getDBConnection();

    date_default_timezone_set("Europe/Prague");


    //názvy dnů v češtině
    $namesOfDays = array('Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle');

    $namesOfMonthsCzech = array('Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec');
    //počet dní v měsíci
    $numberOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    //číslo prvního dne měsíce
    $dayOfWeek = date("N", mktime(0, 0, 0, $month, 1, $year));
    //číslo měsíce, leading 0 kvůli $namesOfMonthsCzech
    $monthNumber = date("n", mktime(0, 0, 0, $month, 1, $year)) - 1;
    //první den
    $dayCounter = 1;


    if ($iteration == 0) {
        $calendar = "<table class='calendar' id='$month-$year'>";
    } else {
        $calendar = "<table class='calendar' style='display: none' id='$month-$year'>";
    }
    $calendar .= "<caption class='monthIdentifier'>$namesOfMonthsCzech[$monthNumber] $year</caption>";
    $calendar .= "<tr>";

    foreach ($namesOfDays as $dayName) {
        $calendar .= "<th class='headers'>$dayName</th>";
    }

    $calendar .= "</tr><tr>";

    //pokud měsíc nezačíná pondělkem, tak vyplníme prázdným <td>
    if ($dayOfWeek > 1) {
        $freeDays = $dayOfWeek - 1;
        $calendar .= "<td colspan='$freeDays'></td>";
    }


    while ($dayCounter <= $numberOfDaysInMonth) {
        /**
         * Po sedmém dni začneme nový řádek tabulky
         */
        if ($dayOfWeek == 8) {
            $calendar .= "</tr><tr>";
            $dayOfWeek = 1;
        }
        //$loopMysqlDate = date("Y-m-d", strtotime("$year-$month-$dayCounter"));
        $loopDate = new DateTime("$year-$month-$dayCounter");
        $loopMysqlDate = $loopDate->format("Y-m-d");
        /**
         * Dotaz zda je pro aktuální den volno
         */
        $statementFreeTime = $mysql->prepare("SELECT * FROM rezervace WHERE jednotka_id_jednotka = ? AND ? BETWEEN datum_prijezd AND datum_odjezd");
        $statementFreeTime->execute(array($apartman, $loopMysqlDate));
        /**
         * Dotaz na cenu za noc,
         * cenové sazby mají rok 0001,
         * proto je potřeba porovnávat pouze den a měsíc
         */
        $statementPrice = $mysql->prepare("SELECT cena FROM cenova_sazba WHERE jednotka_id_jednotka = ? AND DATE_FORMAT(? ,'%m-%d') BETWEEN DATE_FORMAT(datum_od, '%m-%d') AND DATE_FORMAT(datum_do, '%m-%d')");
        $statementPrice->execute(array($apartman, $loopMysqlDate));

        $price = $statementPrice->fetch();
        /**
         * Prověříme dostupnost a cenu u dnů pozdějších než aktuální datum
         */
        if ($loopDate >= new DateTime()) {

            if ($statementFreeTime->rowCount() >= 1) {
                $calendar .= "<td class='day taken' id='$loopMysqlDate'>$dayCounter</td>";
            } else {
                if ($statementPrice->rowCount() >= 1) {
                    $calendar .= "<td class='day after' id='$loopMysqlDate'>$dayCounter<br>$price[0] Kč</td>";
                } else {
                    $calendar .= "<td class='day before'>$dayCounter</td>";
                }
            }
        } else {
            $calendar .= "<td class='day before'>$dayCounter</td>";
        }
        $dayCounter++;
        $dayOfWeek++;
    }


    if ($dayOfWeek != 7) {
        //ne od sedmi, ale osmi, protože v while přidáváme jeden den
        $remainingDays = 8 - $dayOfWeek;
        $calendar .= "<td colspan='$remainingDays'></td>";
    }
    $calendar .= "</tr></table>";


    echo $calendar;
}

