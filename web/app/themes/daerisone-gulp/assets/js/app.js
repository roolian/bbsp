$(document).ready(function () {
    $(".openRequestTrial")
        .find("a")
        .on("click", function (e) {
            e.preventDefault();
            $("#requestTrial").modal("toggle");
            //console.log(1);
        });

    $(".openContact")
        .find("a")
        .on("click", function (e) {
            e.preventDefault();
            $("#contactUs").modal("toggle");
            //console.log(1);
        });

    if (window.location.href.indexOf("#free-trial") != -1) {
        $("#requestTrial").modal("show");
    }
});
