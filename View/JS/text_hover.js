
$(document).ready(function() {
    
    $('.events-outstand .card-content, .events-outstand-b .card-content').mouseenter(function() {
        $(this).find('.mensaje-hover-destacado').css('display', 'flex').hide().fadeIn(300);
    });

    $('.mensaje-hover-destacado').mouseleave(function() {
        $(this).fadeOut(200);
    });

});