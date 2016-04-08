$(document).ready(function() {
    $(".closeNotification").click(function () {
        console.log("TEST");
        $(this).parent().slideUp();
    });
});