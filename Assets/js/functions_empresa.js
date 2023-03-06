let tableEmpresas;
let rowTable = "";
let tableContacts;
document.addEventListener('DOMContentLoaded', function () {

    //Nueva Empresa
    let formEmpresa = document.querySelector("#formEmpresa");
    formEmpresa.onsubmit = function (e) {
        e.preventDefault();
        let nombreE = $("#txtNombreEmpresa").val();
        let rutE = $("#txtRutEmpresa").val();
        let ocupacion = $("#txtOcupacion").val();
        if (nombreE == '' || rutE == '' || ocupacion == '')
        {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Empresas/setEmpresa';
            let formData = new FormData(formEmpresa);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableEmpresas.api().ajax.reload();
                        $('#modalFormEmpresa').modal('hide');
                        formEmpresa.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            };
        }
    };
    cargarEmpresas();
}, false);

function cargarEmpresas() {
    tableEmpresas = $('#tableEmpresa').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Empresas/getEmpresas",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombre"},
            {"data": "rubro"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });
}

function fntViewInfo(idEmpresa) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Empresas/getEmpresa/' + idEmpresa;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#celRut").innerHTML = objData.data.rut;
                document.querySelector("#celNombreE").innerHTML = objData.data.nombre;
                document.querySelector("#celOcupacion").innerHTML = objData.data.rubro;
                document.querySelector("#celEstadoE").innerHTML = estado;
                $('#modalViewEmpresa').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idempresa) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Empresa";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Empresas/getEmpresa/' + idempresa;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idEmpresa").value = objData.data.id;
                document.querySelector("#txtNombreEmpresa").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtRutEmpresa").value = objData.data.rut;
                document.querySelector("#txtOcupacion").value = objData.data.rubro.toString().toLowerCase();
                $('#modalFormEmpresa').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idEmpresa) {
    swal({
        title: "Inhabilitar Empresa",
        text: "¿Realmente quiere inhabilitar esta empresa?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Empresas/setStatusEmpresa';
            let strData = "idEmpresa=" + idEmpresa + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableEmpresas.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idEmpresa) {
    swal({
        title: "Habilitar Empresa",
        text: "¿Realmente quiere habilitar esta empresa?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Empresas/setStatusEmpresa';
            let strData = "idEmpresa=" + idEmpresa + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitada !!", objData.msg, "success");
                        tableEmpresas.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}



function openModal() {
    document.querySelector('#idEmpresa').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "  Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Empresa";
    document.querySelector("#formEmpresa").reset();
    validadorRut('txtRutEmpresa');
    $('#modalFormEmpresa').modal('show');
}

function getPersons(idEmpresa) {
    tableContacts = $('#tablePersonsE').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "lengthChange": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Empresas/getPersonsEmpresa/" + idEmpresa,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nombre"},
            {"data": "nombreEmpresa"},
            {"data": "correo"},
            {"data": "fono"}
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "desc"]]
    });
    $('#modalListPersons').modal('show');
    $(".titlePersons").text("Guias Afiliados a la Empresa");
}



function generarReportEmpresa() {
    $.post(base_url + "/Empresas/getEmpresasReport",
            function (response) {
                var fecha = new Date();
                var empresas = JSON.parse(response);
                if (empresas.length > 0) {
                    //console.log(empresas);
                    let estado = "";
                    var pdf = new jsPDF();
                    pdf.text(20, 20, "Reportes de las Empresas Registradas");
                    var data = [];
                    var columns = ["RUT", "NOMBRE", "RUBRO", "GUIAS", "ESTADO"];
                    for (let i = 0; i < empresas.length; i++) {
                        if (empresas[i].status == 1) {
                            estado = 'ACTIVO';
                        } else {
                            estado = 'INACTIVO';
                        }
                        data[i] = [empresas[i].rut, empresas[i].nombre, empresas[i].rubro, empresas[i].listPersons, estado];
                    }
                    pdf.autoTable(columns, data,
                            {margin: {top: 40}}
                    );
                    pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                    pdf.save('ReporteEmpress.pdf');
                    swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
                } else {
                    swal("Error !!", "No se cuentan con empresas registradas !!", "error");
                }
            }
    );
}