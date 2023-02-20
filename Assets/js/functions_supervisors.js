let tableAdmins;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    validadorRut('txtDni');
    tableAdmins = $('#tableSupervisors').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Supervisores/getSupervisores",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombre"},
            {"data": "correo"},
            {"data": "status"},
            {"data": "options"}
        ],
        "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });
    //NUEVO ADMIN
    let formSupervisor = document.querySelector("#formSupervisor");
    formSupervisor.onsubmit = function (e) {
        e.preventDefault();
        let txtRut = document.querySelector('#txtDni').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let strApellido = document.querySelector('#txtApellido').value;
        let txtCorreo = $('#txtEmail').val();
        var regexCoreo = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        //var regexClave = new RegExp("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$");
        if (strNombre == '' || strApellido == '' || txtRut == '' || txtCorreo == '')
        {
            swal("Atención", "Debe ingresar datos para crear al Admin", "error");
            return false;
        } else if (!regexCoreo.test(txtCorreo.trim())) {
            swal("Por favor", "Ingrese un Correo Valido para registrarse...", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisores/setSupervisor';
            let formData = new FormData(formSupervisor);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableAdmins.api().ajax.reload();
                        $('#modalFormSupervisors').modal("hide");
                        formSupervisor.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };

    let formColegioA = document.querySelector("#formColegioA");
    formColegioA.onsubmit = function (e) {
        e.preventDefault();
        let listColegios = document.querySelector("#listColegios").value;
        let txtTelefono = $('#txtTelefono').val();
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;

        if (listColegios == '' || txtTelefono == '') {
            swal("Atención", "Debe ingresar datos para crear al Admin", "error");
            return false;
        } else if (!regulTele.test(txtTelefono.trim())) {
            swal("Por favor", "Ingrese un Telefono Valido para registrarse...", "error");
            $("#txtTelefono").val("+569");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisores/setDetailColegio';
            let formData = new FormData(formColegioA);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableAdmins.api().ajax.reload();
                        $('#modalFormColegiosAd').modal("hide");
                        formColegioA.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };

    fntColegios();
}, false);

function fntViewInfo(idAdmin) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisores/getSupervisor/' + idAdmin;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-dark">Vinculado</span>';
                document.querySelector("#celRut").innerHTML = objData.data.rut;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celEmail").innerHTML = objData.data.correo;
                document.querySelector("#celDireccion").innerHTML = objData.data.direccion != "" ? objData.data.direccion : "No se tiene una direccion registrada";
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celStatus").innerHTML = estado;

                $('#modalViewSupervisor').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idAdmin) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Supervisor de Colegio";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisores/getSupervisor/' + idAdmin;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idSupervisor").value = objData.data.id;
                document.querySelector("#txtDni").value = objData.data.rut;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtApellido").value = objData.data.apellido.toString().toLowerCase();
                document.querySelector("#txtEmail").value = objData.data.correo.toString().toLowerCase();
                document.querySelector("#txtDireccion").value = objData.data.direccion != "" ? objData.data.direccion.toString().toLowerCase() : "";

                $('#modalFormSupervisors').modal('show');

            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function fntDelInfo(idadmin) {
    swal({
        title: "Inhabilitar Supervisor",
        text: "¿Realmente quiere inhabilitar a este supervisor?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisores/setStatusSupervisor';
            let strData = "idSupervisor=" + idadmin + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado !!", objData.msg, "success");
                        tableAdmins.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function fntActivateInfo(idadmin) {
    swal({
        title: "Habilitar Supervisor",
        text: "¿Realmente quiere habilitar a este supervisor?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisores/setStatusSupervisor';
            let strData = "idSupervisor=" + idadmin + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Activada !!", objData.msg, "success");
                        tableAdmins.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });

}

function openModal() {
    document.querySelector('#idSupervisor').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Supervisor de Colegio";
    document.querySelector("#formSupervisor").reset();
    $('#modalFormSupervisors').modal('show');
}


/*Funciones para el añadir/actualizar colegio por parte del Admin*/

function fntSchoolA(idAdmin) {
    document.querySelector('#titleModalA').innerHTML = "Agregar Colegio al Supervisor";
    document.querySelector('#btnActionFormA').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnTextA').innerHTML = "Guardar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisores/getSupervisor/' + idAdmin;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idSupervisorC").value = objData.data.id;
                document.querySelector("#idVinCol").value = "";
                document.querySelector("#txtDniA").value = objData.data.rut;
                document.querySelector("#txtNombreA").value = objData.data.nombre;
                document.querySelector("#txtTelefono").value = "+569";
                document.querySelector("#listColegios").value = "0";
                $("#modalFormColegiosAd").modal('show');
            }
        }
    };
}
function fntSchoolU(idAdmin) {
    document.querySelector('#titleModalA').innerHTML = "Actualizar Colegio al Supervisor";
    document.querySelector('#btnActionFormA').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnTextA').innerHTML = "Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisores/getSupervisor/' + idAdmin;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idSupervisorC").value = objData.data.id;
                document.querySelector("#idVinCol").value = objData.data.school.idVin;
                document.querySelector("#txtDniA").value = objData.data.rut;
                document.querySelector("#txtNombreA").value = objData.data.nombre;
                document.querySelector("#txtTelefono").value = objData.data.school.telefono;
                document.querySelector("#listColegios").value = objData.data.school.id;
                $("#modalFormColegiosAd").modal('show');
            }
        }
    };
}

function fntDelSchool(idadmin) {
    swal({
        title: "Remover Colegio",
        text: "¿Realmente quiere quitar este colegio?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisores/removeSchoolSupervisor';
            let strData = "idSupervisor=" + idadmin;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !!", objData.msg, "success");
                        tableAdmins.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntColegios() {
    if (document.querySelector('#listColegios')) {
        $.ajax({
            type: "POST",
            url: base_url + '/Colegios/getSelectColegios',
            success: function (data) {
                $('.selectColegios select').html(data).fadeIn();
            }
        });
    }
}

function generarReportSupervisors() {
    $.post(base_url + "/Supervisores/getSupervisorReport",
            function (response) {
                var fecha = new Date();
                let adminsColegio = JSON.parse(response);
                //console.log(notificaciones);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                var columns = ["RUT", "NOMBRE", "TELEFONO", "DIRECCION", "ESTADO"];
                var data = [];

                for (let i = 0; i < adminsColegio.length; i++) {
                    if (adminsColegio[i].status == 1) {
                        estado = "ACTIVO";
                    } else {
                        estado = "VINCULADO";
                    }

                    let telefono = "No se tiene un telefono registrado";
                    let direccion = "No tiene una dirección registrada";

                    if (adminsColegio[i].school) {
                        telefono = adminsColegio[i].school.telefono || "No se tiene un telefono registrado";
                        direccion = adminsColegio[i].direccion || "No tiene una dirección registrada";
                    }

                    data[i] = [adminsColegio[i].rut,
                        adminsColegio[i].nombre,
                        telefono,
                        direccion,
                        estado
                    ];
                }


                pdf.text(20, 20, "Reportes de los Supervisores Registrados");

                pdf.autoTable(columns, data,
                        {margin: {top: 40}}
                );


                pdf.text(20, pdf.autoTable.previous.finalY + 20, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReportSupervisors.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
            }
    );
}