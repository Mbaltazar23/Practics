$(document).ready(function () {
    validadorRut('txtRut');
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
                    let ajaxUrl = base_url + '/Home/setPortadaPerfil';
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
                                swal({
                                    title: "Exito !",
                                    text: objData.msg,
                                    icon: "success"
                                }).then(function () {
                                    location.reload();
                                });
                                contactAlert.innerHTML = '';
                                if (document.querySelector('#img')) {
                                    document.querySelector('#img').remove();
                                }
                                document.querySelector('.delPhoto').classList.remove("notBlock");
                                //let objeto_url = nav.createObjectURL(this.files[0]);
                                document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + base_url + '/Assets/images/perfil/' + objData.imgname + " width='140' height='120'>";
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
                    let ajaxUrl = base_url + '/Home/delPortada';
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
        let txtRut = $("#txtRut").val();
        let txtNombre = $("#txtNombre").val();
        let txtApellido = $("#txtApellido").val();
        let password01 = $("#txtPassword").val();
        let password02 = $("#txtPasswordConfirm").val();
        if (txtRut == "" || txtNombre == "" || txtApellido == "") {
            swal("Por favor", "Estos campos no pueden estar vacios...", "error");
            return false;
        } else if (password01.length > 20 || password01 != password02) {
            swal("Error", "La contraseña ingresada no coincide o es debil...", "error");
            return false;
            document.querySelector("#txtPassword").value = "";
            document.querySelector("#txtPasswordConfirm").value = "";
        } else {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Home/setPasswordUser';
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
                        swal({
                            title: "Exito !",
                            text: objData.msg,
                            icon: "success"
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            };
        }

    };
    getUserPerfil();
});



function openModalPerfil() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Home/getUser';
    request.open("POST", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idPerPass").value = objData.data.id;
                document.querySelector("#txtRut").value = objData.data.rut;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtApellido").value = objData.data.apellido.toString().toLowerCase();
                document.querySelector("#txtEmail").value = objData.data.correo.toString().toLowerCase();
                document.querySelector("#txtDireccion").value = objData.data.direccion != "" ? objData.data.direccion.toString().toLowerCase() : "";
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


function getUserPerfil() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Home/getUser';
    request.open("POST", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                console.log(objData.data);
                document.querySelector("#nameUser").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#rolUser").innerHTML = objData.data.rol;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celApellido").innerHTML = objData.data.apellido;
                document.querySelector("#celEmail").innerHTML = objData.data.correo;
                document.querySelector("#celRol").innerHTML = objData.data.rol;
                document.querySelector("#celDireccion").innerHTML = objData.data.direccion != "" ? objData.data.direccion : "No se tiene una direccion registrada";
                document.querySelector("#celAvatar").innerHTML = '<img src="' + objData.data.url_portada + '" width="120" height="100"/>';

            }
        }
    };
}
