$(document).ready(function () {
    alert("Rezervační systém je zatím ve zkušební verzi!");
    $(".tab").first().show();

    $("#firstStep").parent().click(function (e) {
        e.preventDefault();

        $(".tab").hide();
        $("#personTab").show();
    });

    $("#secondStep").parent().click(function (e) {
        e.preventDefault();
        if ($("#pocetOsob").val() != 0) {
            $(".tab").hide();
            $("#listRoomsTab").show();
        } else {
            $(this).effect("shake", {distance: 7});
        }
    });

    $("#thirdStep").parent().click(function (e) {
        e.preventDefault();
        if ($(".activeFilter").length >= 1) {
            $(".tab").hide();
            $("#calendarTab").show();
        } else {
            $(this).effect("shake", {distance: 7});
        }
    });

    $("#fourthStep").parent().click(function (e) {
        e.preventDefault();
        if ($(".selected").length >= 2) {
            $(".tab").hide();
            $("#additionsTab").show();
        } else {
            $(this).effect("shake", {distance: 7});
        }
    });

    $("#fifthStep").parent().click(function (e) {
        e.preventDefault();
        if ($("#additionsTab").is(":visible")) {
            $(".tab").hide();
            $("#infoTab").show();
        } else {
            $(this).effect("shake", {distance: 7});
        }
    });
    /**
     * Handler pro zobrazení následujícího elementu formuláře pomocí jQuery identifikátoru $(this)
     * Kvůli využití AJAXu je potřebné prověřovat validitu
     */
    $(".nextButton").click(function (e) {
        e.preventDefault();

        if ($("#personTab").is(":visible") && $("#pocetOsob").val() == 0) {
            alert("Není vybrán počet osob.");
            $("#pocetOsob").focus();
        } else if ($("#listRoomsTab").is(":visible") && $(".activeFilter").length < 1) {
            alert("Není vybrán apartmán.");
        } else if ($("#calendarTab").is(":visible") && $(".selected").length < 2) {
            alert("Nejsou vybrány dny příjezdu a odjezdu");
        } else {
            $(this).closest(".tab").next().fadeIn('slow');
            $(this).closest(".tab").hide();
        }
    });


    $("body").keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });



    /**
     * Handler pro zobrazení předchozího elementu formuláře
     */
    $(".backButton").click(function (e) {
        e.preventDefault();
        $(this).parent().parent().prev().fadeIn('slow');
        $(this).closest(".tab").hide();
    });






    /**
     * Odstraní znak splněného kroku a označené dny včetně jejich hodnot ve formuláři
     */
    $("#removeSelected").click(function (e) {
        e.preventDefault();
        $("#thirdStep").toggleClass("fa-times-circle");
        $(".after").removeClass("selected");
        $("#prijezd").val("");
        $("#odjezd").val("");
    });

    /**
     * Navazující funkce a handlery na AJAX je potřeba
     * vkládat do callbackových funkcí, které počkají na splnění
     * asynchronního požadavku
     */
    $("#pocetOsob").change(function () {
        $(".roomButton").removeClass("activeFilter");
        $("#firstStep").toggleClass("fa-check-circle");
        $(".calendar").remove();
        /**
         * Při změně hodnoty počtu osob odešleme GET dotaz,
         * který vrátí elementy pro každý pokoj splňující limit osob
         */
        $.ajax({
            type: "GET",
            url: "listRooms.php",
            data: {
                pocetOsob: $("#pocetOsob").val()
            },
            success: function (data) {
                /**
                 * Připojíme přijatá data k DOM
                 */
                $("#listRoomsTab").find("#roomList").remove();
                $("#listRoomsTab").append(data);
                /**
                 * Stejný princip po výběru pokoje, je potřeba zavolat
                 * metodu preventDefault(), jelikož tlačítko
                 * funguje jako submit formuláře
                 */
                $(".roomButton").click(function (e) {
                    e.preventDefault();

                    $("#secondStep").toggleClass("fa-check-circle");
                    $(".calendar").remove();

                    var id = this.id.charAt(8);
                    $("#idApartman").val(id);
                    /**
                     * Vždy jen jedno aktivní tlačítko
                     */
                    $(this).toggleClass("activeFilter");
                    $(".roomButton").not($(this)).removeClass("activeFilter");
                    /**
                     * Metoda pro asynchronní vygenerování kalendáře
                     */
                    load(id);


                    $("#reservationForm").submit( function (e) {
                        e.preventDefault();
                        /**
                         * Po potvrzení odešleme AJAX požadavek do PHP skriptu
                         * pro vytvoření rezervace
                         */
                        if (window.confirm("Potvrzujete rezervaci?")) {
                            $(".ajaxSuccess").remove();
                            var url = "createReservation.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                /**
                                 * Hodnoty formuláže serializujeme pro zpracování
                                 */
                                data: $("#reservationForm").serialize(),
                                success: function (data) {
                                    $("#reservationForm").hide();
                                    $("#reservation").append(
                                        "<div class='ajaxSuccess'>" +
                                        data + "</div>");
                                    $("#fifthStep").toggleClass("fa-check-circle");

                                },
                                error: function (error) {
                                    console.log(error);
                                    $("#reservation").append(
                                        "<div class='ajaxSuccess'>" +
                                        error.responseText + "</div>");
                                }
                            });
                        } else {
                            return false;
                        }
                    });
                })
            }
        })
    });
});