let tableCursos;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableCursos = $('#tableCursos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Cursos/getCursos",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "fecha"},
            {"data": "hora"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    //NUEVO CURSO
    let formCursos = document.querySelector("#formCursos");
    formCursos.onsubmit = function (e) {
        e.preventDefault();
        let nombreCategoria = $("#txtNombre").val();

        if (nombreCategoria == "") {
            swal("Atención", "Debe ingresar el nombre al curso.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Cursos/setCurso';
            let formData = new FormData(formCursos);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableCursos.api().ajax.reload();
                        $('#modalFormCursos').modal("hide");
                        formCursos.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };
}, false);

function fntViewInfo(nro, idCurso) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Cursos/getCurso/' + idCurso;
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

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewCurso').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idCurso) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Curso";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Cursos/getCurso/' + idCurso;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idCurso").value = objData.data.id;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLowerCase();
                $('#modalFormCursos').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idCurso) {
    swal({
        title: "Inhabilitar Curso",
        text: "¿Realmente quiere inhabilitar este curso",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Cursos/setStatusCurso';
            let strData = "idCurso=" + idCurso + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableCursos.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idCurso) {
    swal({
        title: "Habilitar Curso",
        text: "¿Realmente quiere habilitar este curso?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Cursos/setStatusCurso';
            let strData = "idCurso=" + idCurso + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableCursos.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idCurso').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "  Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Curso";
    document.querySelector("#formCursos").reset();
    $('#modalFormCursos').modal('show');
}


function generarReportCursos() {
    $.post(base_url + "/Cursos/getCursosReport",
            function (response) {
                var fecha = new Date();
                var cursos = JSON.parse(response);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                pdf.text(20, 20, "Reportes de los Cursos Registrados");
                var data = [];
                var columns = ["NRO", "NOMBRE", "FECHA/HORA", "ESTADO"];
                for (let i = 0; i < cursos.length; i++) {
                    if (cursos[i].status == 1) {
                        estado = 'ACTIVO';
                    } else {
                        estado = 'INACTIVO';
                    }
                    data[i] = [(i + 1), cursos[i].nombre, cursos[i].fecha + " " + cursos[i].hora, estado];
                }
                pdf.autoTable(columns, data,
                        {margin: {top: 40}}
                );
                pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReporteCursos.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
            }
    );
}
