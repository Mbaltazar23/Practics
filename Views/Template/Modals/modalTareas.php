<!--Modal de la insercion/actualizacion de los tareas a manipular-->
<div class="modal fade" id="modalFormTareas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTareas" name="formTareas" class="form-horizontal">
                    <input type="hidden" id="idTarea" name="idTarea" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombreTarea" name="txtNombreTarea" type="text"/>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnText"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal donde se vera el detalle de la tarea registrada-->
<div class="modal fade" id="modalViewTarea" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nro:</strong></td>
                            <td id="celNroT"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreT"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoT"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Modal para mirar las notas que fueron obtenidas de -->
<div class="modal fade" id="modalViewCal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nro:</strong></td>
                            <td id="celNroCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreTCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoTCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Notas:</strong></td>
                            <td id="celNotasCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Promedio</strong></td>
                            <td id="celPromedioCal"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalFormBitacora" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalB"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTareasB" name="formTareasB" class="form-horizontal">
                    <input type="hidden" id="idTareaB" name="idTareaB" value="">
                    <input type="hidden" id="idSub" name="idSub" value="">
                    <div class="form-group">
                        <label class="control-label">Tarea</label>
                        <input class="form-control" id="txtNombreT" name="txtNombreT" type="text" disabled/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Fecha</label>
                        <input class="form-control" id="txtFecha" name="txtFecha" type="date" />
                    </div>

                    <div class="form-group">
                        <label class="control-label">Bitacora a subir</label>
                        <textarea class="form-control" id="txtBitacora" name="txtBitacora" type="text"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionFormB" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextB">&nbsp;>&nbsp;</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!--Modal que servira para la insercion de imagenes para el documento a subir-->
<div class="modal fade" id="modalFormDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="titleDoc"></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formDoc" name="formDoc" class="form-horizontal">
                    <input type="hidden" id="idDoc" name="idDoc"/>
                    <div class="form-group">
                        <label class="control-label">Titulo</label>
                        <input class="form-control" id="txtTitulo" name="txtTitulo" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Documento/Texto</label>
                        <textarea class="form-control" id="txtDocumento" name="txtDocumento" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionFormD" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextD">&nbsp;&nbsp;</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalDocumentIMG" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="myModalLabel"><span id="nombreIMG"></span></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <input type="hidden" id="idPA"/>
                    <div class="form-group">
                        <p><strong>Titulo:</strong>&nbsp;&nbsp;<span id="txtTit"></span></p>
                        <p><strong>Texto :</strong>&nbsp;&nbsp;<span id="txtText"></span></p>
                    </div>
                    <div class="form-group">
                        <div id="containerGallery">
                            <span>Agregar fotos(evidencia) para el gasto registrado</span> &nbsp;
                            <button class="btnAddImage btn btn-info" type="button">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <hr>
                        <div id="containerImages">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalDocumentNote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Documentacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Titulo:</strong></td>
                            <td id="celTit"></td>
                        </tr>
                        <tr>
                            <td><strong>Texto:</strong></td>
                            <td id="celText"></td>
                        </tr>
                        <tr>
                            <td><strong>Nota:</strong></td>
                            <td id="celNotaD"></td>
                        </tr>
                        <tr>
                            <td><strong>Evaluado el:</strong></td>
                            <td id="celFechaD"></td>
                        </tr>
                        <tr>
                            <td><strong>Comentarios:</strong></td>
                            <td id="celComentariosD"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
