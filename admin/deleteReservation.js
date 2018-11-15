$(document).ready(function () {

    $("#deleteReservation").click(function (e) {

        if (window.confirm("Opravdu chcete tuto rezervaci smazat?")) {
            window.location.replace("reservations.php");
        } else {
            e.preventDefault();
        }
    });

});


