$(document).ready(function () {

    if ($("#formLogin").length > 0) {
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
    }

    if ($("#formRegistro").length > 0) {
        $("#formRegistro").on("submit", function (evento) {
            evento.preventDefault();

            const nombre = $("#registroNombre").val().trim();
            const correo = $("#registroCorreo").val().trim();
            const password = $("#registroPassword").val();
            const confirmarPassword = $("#registroConfirmarPassword").val();
            const mensaje = $("#mensajeRegistro");

            mensaje.text("");

            if (
                nombre === "" ||
                correo === "" ||
                password === "" ||
                confirmarPassword === ""
            ) {
                mensaje.text("Todos los campos son obligatorios.");
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

            if (password !== confirmarPassword) {
                mensaje.text("Las contraseñas no coinciden.");
                return;
            }

            $.ajax({
                url: "/seguridad_web/api/usuario.php",
                method: "POST",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    nombre: nombre,
                    correo: correo,
                    password: password
                }),

                success: function (respuesta) {
                    alert(respuesta.mensaje);
                    window.location.href = "index.html";
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
    }

    if ($("#formRecuperar").length > 0) {
        $("#formRecuperar").on("submit", function (evento) {
            evento.preventDefault();

            const correo = $("#recuperarCorreo").val().trim();
            const mensaje = $("#mensajeRecuperar");

            mensaje.text("");

            if (correo === "") {
                mensaje.text("El correo es obligatorio.");
                return;
            }

            const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!formatoCorreo.test(correo)) {
                mensaje.text("Ingrese un correo válido.");
                return;
            }

            $.ajax({
                url: "/seguridad_web/api/recuperar.php",
                method: "POST",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    correo: correo
                }),

                success: function (respuesta) {
                    sessionStorage.setItem(
                        "tokenRecuperacion",
                        respuesta.token
                    );

                    alert(respuesta.mensaje);

                    window.location.href = "reset.html";
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
    }

    if ($("#formReset").length > 0) {
        const tokenGuardado = sessionStorage.getItem("tokenRecuperacion");

        $("#resetToken").val(tokenGuardado);

        $("#formReset").on("submit", function (evento) {
            evento.preventDefault();

            const token = $("#resetToken").val();
            const password = $("#resetPassword").val();
            const confirmarPassword = $("#resetConfirmarPassword").val();
            const mensaje = $("#mensajeReset");

            mensaje.text("");

            if (token === null || token === "") {
                mensaje.text("Primero debe solicitar la recuperación de contraseña.");
                return;
            }

            if (password === "" || confirmarPassword === "") {
                mensaje.text("Todos los campos son obligatorios.");
                return;
            }

            if (password.length < 6) {
                mensaje.text("La contraseña debe tener al menos 6 caracteres.");
                return;
            }

            if (password !== confirmarPassword) {
                mensaje.text("Las contraseñas no coinciden.");
                return;
            }

            $.ajax({
                url: "/seguridad_web/api/reset.php",
                method: "PUT",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    token: token,
                    password: password
                }),

                success: function (respuesta) {
                    sessionStorage.removeItem("tokenRecuperacion");

                    alert(respuesta.mensaje);

                    window.location.href = "index.html";
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
    }
});