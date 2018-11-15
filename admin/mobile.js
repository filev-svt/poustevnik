$(".mobileNav").click(function (e) {
    $("nav").toggle("slide", {direction: "left"});
});

$(".userLogo").click(function () {
   $(".headerRight").toggle("slide", {direction: "up"});
});
