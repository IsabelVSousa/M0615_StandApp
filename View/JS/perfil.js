$(document).ready(function () {

    // Abrir modal
    
    $("#btn-eliminar").on("click", function () {
        $("#modal-eliminar").addClass("activo").hide().fadeIn(200);
    });

    // Cerrar con botón Cancelar
    $("#btn-modal-cancelar").on("click", function () {
        $("#modal-eliminar").fadeOut(200, function () {
            $(this).removeClass("activo");
        });
    });

    // Cerrar clicando fuera del modal
    $("#modal-eliminar").on("click", function (e) {
        if ($(e.target).is("#modal-eliminar")) {
            $(this).fadeOut(200, function () {
                $(this).removeClass("activo");
            });
        }
    });

});