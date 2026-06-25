$(document).ready(function () {

    $("#formLogin").on("submit", function (evento) {
        evento.preventDefault();

        const correo = $("#loginCorreo").val().trim();
        const password = $("#loginPassword").val();
        const mensaje = $("#mensajeLogin");

        mensaje.text("");

        if (correo === "" || password === "") {
            mensaje.text("Correo y contraseña son obligatorios.");
            return;
        }

        const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!formatoCorreo.test(correo)) {
            mensaje.text("Ingrese un correo válido.");
            return;
        }

        if (password.length < 8) {
            mensaje.text("La contraseña debe tener al menos 8 caracteres.");
            return;
        }

        $.ajax({
            url: "/seguridad_web/api/login.php",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({
                correo: correo,
                password: password
            }),

            success: function (respuesta) {
                mensaje.text(respuesta.mensaje);
            },

            error: function (error) {
                if (error.responseJSON) {
                    mensaje.text(error.responseJSON.mensaje);
                } else {
                    mensaje.text("No fue posible comunicarse con el servidor.");
                }
            }
        });
    });

});