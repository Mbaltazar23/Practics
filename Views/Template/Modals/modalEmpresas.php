<!--Modal de la insercion/actualizacion de las empresas a manipular-->
<div class="modal fade" id="modalFormEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEmpresa" name="formEmpresa" class="form-horizontal">
                    <input type="hidden" id="idEmpresa" name="idEmpresa" value="">
                    <div class="form-group">
                        <label class="control-label">Rut</label>
                        <input class="form-control" id="txtRutEmpresa" name="txtRutEmpresa" type="text" maxlength="12"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombreEmpresa" name="txtNombreEmpresa" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Giro</label>
                        <textarea class="form-control" id="txtOcupacion" name="txtOcupacion" type="text"></textarea>
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


<!--Modal donde se vera el detalle del contacto registrado-->
<div class="modal fade" id="modalViewEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Rut:</strong></td>
                            <td id="celRut"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreE"></td>
                        </tr>
                        <tr>
                            <td><strong>Giro:</strong></td>
                            <td id="celOcupacion"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoE"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Modal donde se liste con Datatable los contactos que esten registrados en la empresa-->
<div class="modal fade" id="modalListPersons" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4 class="modal-title titlePersons"></h4></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">  
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-responsive-lg" id="tablePersonsE" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>  
                                        <th>Empresa</th>
                                        <th>Correo</th>
                                        <th>Telefono</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
