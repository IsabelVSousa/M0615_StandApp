$(document).ready(function() {

    $('#carrusel-eventos-slick').slick({
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 742,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    $('#carrusel-comentarios-slick').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        pauseOnHover: true,
        responsive: [
            {
                breakpoint: 1065,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 742,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

});

function mostrarFecha(input) {
    if (input.value) {
        const partes = input.value.split('-');
        document.getElementById('fecha-texto').textContent =
            partes[2] + '/' + partes[1] + '/' + partes[0];
        input.form.submit();
    }
}