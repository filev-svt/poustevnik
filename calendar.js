function load(apartman) {
    /**
     * AJAX požadavek typu GET pro zobrazení kalendáře pro daný apartmán
     * Následující handlery a funkce jsou ve formě callbackové funkce, jelikož se jedná o asynchronní požadavky
     */
    $.get("calendar.php", {
        apartman: apartman
    }, function (data) {
        $("#calendarTab").append(data);

        $("#previousMonth").click(function (e) {
            e.preventDefault();
            var currentCalendar = $(".calendar:visible");
            if (currentCalendar.is($(".calendar:first"))) {
                return false;
            } else {
                currentCalendar.hide();
                currentCalendar.prev().show();
            }
        });

        $("#nextMonth").click(function (e) {
            e.preventDefault();
            var currentCalendar = $(".calendar:visible");
            if (currentCalendar.is($(".calendar:last"))) {
                return false;
            } else {
                currentCalendar.hide();
                currentCalendar.next().show();
            }

        });

        $(".after").click(function () {

            $(this).toggleClass("selected");

            if ($(".selected").length > 1 && $(".selected").length <= 15) {

                $("#thirdStep").toggleClass("fa-check-circle");

                $("td").each(function () {
                    /**
                     * Porovnáme atribut id ve formátu MySQL DATE
                     * s prvním a posledním dnem návštěvy a označíme i dny mezi,
                     * popřípadě všechny vymažeme pokud jsou
                     * mezi nimi obsazené termíny
                     */
                    if (($(this).attr("id") > $(".selected:first").attr("id"))
                        && ($(this).attr("id") < $(".selected:last").attr("id"))) {
                        if ($(this).next().hasClass("taken")){
                            /**
                             * Obsazené termíny mezi prvním a posledním dnem
                             */
                            $("#removeSelected").trigger("click");
                            alert("Obsahuje obsazené termíny!");
                            return false;
                        } else {
                            /**
                             * Označíme celý interval pobytu třídou selected
                             */
                            $(this).addClass("selected");
                        }
                    }
                });
            }

            if ($(".selected").length > 15) {
                $("#removeSelected").trigger("click");
                alert("Nelze rezervovat více než 14 nocí.");

            }



            $("#prijezd").val($(".selected:first").attr("id"));
            $("#odjezd").val($(".selected:last").attr("id"));

        })
    });
}
