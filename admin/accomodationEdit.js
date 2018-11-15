$(".pricesEdit").click(function (e) {
    e.preventDefault();

    $("input").prop("readonly", false);
});

$(".pricesForm").submit(function (e) {
    if (!window.confirm("Opravdu potvrzujete změny sazby za noc?")) {
        e.preventDefault();
    }
});