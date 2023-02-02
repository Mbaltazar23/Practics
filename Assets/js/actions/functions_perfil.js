$('#SesionModal').click(function () {
    swal({
        title: "Cerrar Sesion",
        text: "¿Realmente salir de su sesion?",
        icon: "info",
        buttons: true,
        dangerMode: false
    }).then((isClosed) => {
        if (isClosed) {
            cerrarSesion();
            $.ajax({
                type: "POST",
                url: urlIndex + "/logout",
                success: function (data) {
                    if (data) {
                        window.location = urlIndex;
                    }
                }
            });
        }
    });
});


function cerrarSesion() {
    $.ajax({
        type: 'GET',
        url: urlIndex + "/home/setSesion/" + id,
        success: function (data) {
            if (data) {
                console.log("CerrarSesion");
            }
        }
    })
}

$(document).ready(function () {
    if (document.querySelector("#foto")) {
        let foto = document.querySelector("#foto");
        foto.onchange = function (e) {
            e.preventDefault();
            let idpersona = document.querySelector("#idPerPass").value;
            let uploadFoto = document.querySelector("#foto").value;
            let fileimg = document.querySelector("#foto").files;
            let contactAlert = document.querySelector('#form_alert');
            if (uploadFoto != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    foto.value = "";
                    return false;
                } else {
                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = urlIndex + '/Home/setPortadaPerfil';
                    let formData = new FormData();
                    formData.append('idpersona', idpersona);
                    formData.append("foto", this.files[0]);
                    request.open("POST", ajaxUrl, true);
                    request.send(formData);
                    request.onreadystatechange = function () {
                        if (request.readyState != 4)
                            return;
                        if (request.status == 200) {
                            let objData = JSON.parse(request.responseText);
                            if (objData.status) {
                                swal("Exito !!", objData.msg, "success");
                                contactAlert.innerHTML = '';
                                if (document.querySelector('#img')) {
                                    document.querySelector('#img').remove();
                                }
                                document.querySelector('.delPhoto').classList.remove("notBlock");
                                //let objeto_url = nav.createObjectURL(this.files[0]);
                                document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + urlIndex + '/Assets/img/perfil/' + objData.imgname + " width='140' height='120'>";
                                document.querySelector('#foto_actual').value = objData.imgname;
                            } else {
                                swal("Error", objData.msg, "error");
                            }
                        }
                    };
                }
            } else {
                swal("Error !!", "No selecciono una foto", "error");
                if (document.querySelector('#img')) {
                    document.querySelector('#img').remove();
                }
            }
        }
    }

    if (document.querySelector(".delPhoto")) {
        let delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function (e) {
            e.preventDefault();
            swal({
                title: "Borrar Portada",
                text: "¿Realmente quiere borrar esta portada de esta categoría?",
                icon: "warning",
                dangerMode: true,
                buttons: true
            }).then((isClosed) => {
                if (isClosed) {
                    document.querySelector("#foto_remove").value = 1;
                    let nameImg = document.querySelector('#foto_actual').value;
                    let idPersona = document.querySelector("#idPerPass").value;
                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = urlIndex + '/Home/delPortada';
                    let formData = new FormData();
                    formData.append('idpersona', idPersona);
                    formData.append("file", nameImg);
                    request.open("POST", ajaxUrl, true);
                    request.send(formData);
                    request.onreadystatechange = function () {
                        if (request.readyState != 4)
                            return;
                        if (request.status == 200) {
                            let objData = JSON.parse(request.responseText);
                            if (objData.status)
                            {
                                swal("Exito !!", objData.msg, "success");
                                removePhoto();
                            } else {
                                swal("", objData.msg, "error");
                            }
                        }
                    };
                }
            });
        };
    }

    let formPasword = document.querySelector("#formPassword");
    formPasword.onsubmit = function (e) {
        e.preventDefault();
        let password01 = $("#txtPassword").val();
        let password02 = $("#txtPassword02").val();
        if (password01 == "" || password02 == "") {
            swal("Por favor", "Estos campos no pueden estar vacios...", "error");
            return false;
        } else if (password01.length > 20 || password01 != password02) {
            swal("Error", "La contraseña ingresada no coincide o es debil...", "error");
            return false;
            document.querySelector("#txtPassword").value = "";
            document.querySelector("#txtPassword02").value = "";
        } else {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = urlIndex + '/Home/setPasswordUser';
            var formData = new FormData(formPasword);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        $('#modalFormPassword').modal('hide');
                        formPasword.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            };
        }

    };
});
function validadorRut(txtRut) {
    document.getElementById(txtRut).addEventListener('input', function (evt) {
        let value = this.value.replace(/\./g, '').replace('-', '');
        if (value.match(/^(\d{2})(\d{3}){2}(\w{1})$/)) {
            value = value.replace(/^(\d{2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4');
        } else if (value.match(/^(\d)(\d{3}){2}(\w{0,1})$/)) {
            value = value.replace(/^(\d)(\d{3})(\d{3})(\w{0,1})$/, '$1.$2.$3-$4');
        } else if (value.match(/^(\d)(\d{3})(\d{0,2})$/)) {
            value = value.replace(/^(\d)(\d{3})(\d{0,2})$/, '$1.$2.$3');
        } else if (value.match(/^(\d)(\d{0,2})$/)) {
            value = value.replace(/^(\d)(\d{0,2})$/, '$1.$2');
        }
        this.value = value;
    });
}


function mostrarModalPassword() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = urlIndex + '/Home/getUser/' + id;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                $("#rutPer").text("RUT : " + objData.data.rut);
                document.querySelector("#idPerPass").value = id;
                document.querySelector("#nombrePer").value = objData.data.nombres.toString().toLowerCase() + " " + objData.data.apellidos.toString().toLowerCase();
                document.querySelector("#correoPer").value = objData.data.correo.toString().toLowerCase();
                if (objData.data.avatar != "") {
                    document.querySelector('#foto_actual').value = objData.data.avatar;
                    document.querySelector("#panelFoto").innerHTML = "Actualizar Portada";
                } else {
                    document.querySelector('#foto_actual').value = "";
                    document.querySelector("#panelFoto").innerHTML = "Agregar Portada";
                }
                document.querySelector("#foto_remove").value = 0;
                if (document.querySelector('#img')) {
                    document.querySelector('#img').src = objData.data.url_portada;
                } else {
                    document.querySelector('.prevPhoto div').innerHTML = `<img id='img' src="${objData.data.url_portada}"  width="140" height="120"/>`;
                }


                if (objData.data.portada == 'perfil-portada.jpg') {
                    document.querySelector('.delPhoto').classList.add("notBlock");
                } else {
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                }
                $('#modalFormPassword').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function removePhoto() {
    document.querySelector('#foto').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img')) {
        document.querySelector('#img').remove();
    }
}

