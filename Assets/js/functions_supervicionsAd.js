let tableSuperviciones;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableSuperviciones = $('#tableSuperviciones').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Supervisiones/getSupervisionsAd",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombres"},
            {"data": "texto"},
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
}, false);


function fntViewSus(nro, idSupervicion) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisiones/getSupervision/' + idSupervicion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector(".rowTeacher").classList.add("show");
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML = objData.data.texto;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celProfe").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewSupervision').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function generarReportSupervicions() {
    $.post(base_url + "/Supervisiones/getReportSupervicions",
            function (response) {
                var fecha = new Date();
                var supervisiones = JSON.parse(response);
                if (supervisiones.length > 0) {
                    //console.log(tecnicos);
                    let estado = "";
                    var pdf = new jsPDF();
                    pdf.text(20, 20, "Reportes de las Supervisiónes Registradas");
                    var data = [];
                    var columns = ["NRO", "SUPERVISIÓN", "PROFESOR", "FECHA/HORA", "ESTADO"];
                    for (let i = 0; i < supervisiones.length; i++) {
                        if (supervisiones[i].status == 1) {
                            estado = 'ACTIVO';
                        } else {
                            estado = 'INACTIVO';
                        }
                        data[i] = [(i + 1), supervisiones[i].texto,
                            supervisiones[i].nombre + " " + supervisiones[i].apellido,
                            supervisiones[i].fecha + " a las " + supervisiones[i].hora,
                            estado];
                    }
                    pdf.autoTable(columns, data,
                            {margin: {top: 40}}
                    );
                    pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                    pdf.save('ReporteSupervisiones.pdf');
                    swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
                } else {
                    swal("Error !!", "No se cuentan con supervicicones hechas por los profesores !!", "error");
                }
            }
    );
}