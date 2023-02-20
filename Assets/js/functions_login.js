$('.login-content [data-toggle="flip"]').click(function () {
    $('.login-box').toggleClass('flipped');
    return false;
});
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector("#formLogin")) {
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function (e) {
            e.preventDefault();

            let strEmail = document.querySelector('#txtEmail').value;
            let strPassword = document.querySelector('#txtPassword').value;
            let regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

            if (strEmail == "" || strPassword == "")
            {
                swal("Por favor", "Escribe usuario y contraseñaa.", "error");
                return false;
            } else if (!regex.test(strEmail.trim())) {
                swal("Error", "El correo ingresado no es valido...", "error");
                return false;
            } else if (strPassword.length > 20) {
                swal("Error", "La contraseña ingresada no es valida...", "error");
                return false;
            } else {
                var ajaxUrl = base_url + '/Home/login';
                var formData = new FormData(formLogin);
                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: ajaxUrl,
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var objData = JSON.parse(data);
                        //console.log(objData.userData.nombreRol);
                        if (objData.status)
                        {
                            swal({
                                title: "Exito !",
                                text: "Bienvenido(a) señor(a) " + objData.personal.nombre,
                                icon: "success"
                            }).then(function () {
                                window.location = base_url + "/dashboard";
                            });
                        } else {
                            swal("Atención", objData.msg, "error");
                            document.querySelector('#txtPassword').value = "";
                        }
                    },
                    error: function () {
                        swal("Atención", "Error en el proceso", "error");
                        document.querySelector('#txtEmail').value = "";
                        document.querySelector('#txtPassword').value = "";
                    }

                });
            }
        };
    }

}, false);