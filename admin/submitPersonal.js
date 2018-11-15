$("#formAdmin").submit(function(e) {
    //zakážeme průběh php
    e.preventDefault();

    var url = "submitAdmin.php";
    //požadavek na zpracování formuláře
    $.ajax({
        type: "POST",
        url: url,
        data: $("#formAdmin").serialize(),
        success: function(data) {
            $("#formAdmin").hide();
            $("#formAdminWrapper").append("<div class='ajaxSuccess'>" + data + "</div>");
        }

    });
});

$("#formPersonal").submit(function(e) {
    //zakážeme průběh php
    e.preventDefault();

    var url = "submitPersonal.php";
    //požadavek na zpracování formuláře
    $.ajax({
        type: "POST",
        url: url,
        data: $("#formPersonal").serialize(),
        success: function(data) {
            $("#formPersonal").hide();
            $("#formPersonalWrapper").append("<div class='ajaxSuccess'>" + data + "</div>");
        }
    });
});
