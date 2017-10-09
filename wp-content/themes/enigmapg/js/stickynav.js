$(document).ready(function () {
    var emenu = $('.emenu');
    var origOffsetY = emenu.offset().top;
    function scroll() {
        if ($(window).scrollTop() >= origOffsetY) {
            $('.emenu').addClass('sticky');
            $('.content').addClass('emenu-padding');
        } else {
            $('.emenu').removeClass('sticky');
            $('.content').removeClass('emenu-padding');
        }
    }
    document.onscroll = scroll;
}); 