$(document).ready(function(){

    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: (target.offset().top - 94)
                    }, 1000); return false;}
            }
        });
    });

    $(".modalBackground").click(function() {
        $(".imageModal").css("display", "none");
        $(".modalBackground").css("display", "none");
        history.replaceState({}, '', '?');
    });



});