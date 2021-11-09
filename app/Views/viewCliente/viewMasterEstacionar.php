<?= $this->extend("templates\clientes\masterCliente")?>
<?= $this->section('content') ?>

estacionar place

<!--
    Cuando un Cliente desea estacionar deberá poder activar una estadía de
estacionamiento seleccionando un vehículo ingresado en su cuenta o también
puede ingresar un dominio de otro vehículo, durante la activación podrá
seleccionar la cantidad de horas o si la estadía es indefinida, si selecciona
indefinida esto activara una estadía sin fecha y hora de finalización mientras que si
el cliente selecciona la cantidad de horas la estadía tendrá fecha y hora de
finalización. Para las estadías indefinidas el Cliente podrá finalizarlas ingresando
en la aplicación.


Estacionar: 
-seleccionar vehiculo
-definida: cantidad de horas : fecha y hora
-indefinida -> estadia sin fecha y hora de finalizacion (cuando se presione finalizar)

 -->

 <div class="main">

            

    <!-- BOF MAIN-BODY -->

    <div class="row">    
        <!-- BOF Horizontal Form -->
        <div class="col-lg-9">

        


            
            <div class="card mb-3">
            <form method="POST" action="<?= base_url('cliente/estacionar'); ?>">
                    <div class="card-header uppercase">
                        <div class="caption">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                            <path d="M0 0h24v24H0V0z" fill="none"/>
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 20 20" width="20px" fill="currentColor">
                            <path d="M0 0h24v24H0V0z" fill="none"/>
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Estacionar
                        </div>
                        
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="form-group row">
                                    
                                    <label class="col-md-3 col-form-label">Vehiculo</label>
                                    <div class="col">
                                        <div class="form-group">
                                            
                                            <label>Seleccione un vehiculo</label>
                                            <select class="form-control" name="dominio_vehiculo" >
                                                
                                                <option disabled selected=inicial>Vehiculos bajo dominio:</option>
                                                <?php foreach ($dominio as $d) : ?>

                                                    <option value=<?= $d['id']; ?>> 
                                                    <?= $d['vehiculo_patente']; ?> --> <?= $d['vehiculo_marca_nombre']; ?>, <?= $d['vehiculo_modelo_nombre']; ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            
                                            <label>Seleccione una zona</label>
                                            <select class="form-control" name="id_zona" >
                                                
                                                <option disabled selected=inicial>Zonas:</option>
                                                <?php foreach ($zonas as $zona) : ?>

                                                    <option value=<?= $zona['id']; ?>><?= $zona['nombre']; ?>, <?= $zona['descripcion']; ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModalToolPop">Registrar nuevo vehiculo</button>
                                            
                                            <div class="modal fade" id="exampleModalToolPop" tabindex="-1" role="dialog" aria-labelledby="exampleModalToolPopTitle" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalToolPopTitle">Registro rapido</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        
                                                            <div class="modal-body">
                                                                <label class="col-md-3 col-form-label">Patente</label>
                                                                <div class="col">

                                                                    <input type="text" name="patente" class="form-control" placeholder="Patente" value="<?=old("patente")?>" >
                                                                    
                                                                    <p >(ingrese la patente en formato AAA-000 ó AA-000-AA ó A-000-AAA, sin espacios ni guiones)</p>
                                                                    <p  style="color: rgb(232,74,103)"> <?= session('patente'); ?></p>
                                                                </div>

                                                                <label  class="col-md-3 col-form-label">Marca</label>
                                                                
                                                                <div class="col">
                                                                    <select class="form-control" id="marcaBox"name="marca" onchange="cargarModelos()" >
                                                                        <option disabled selected=inicial>Seleccione una Marca:</option>
                                                                        <?php foreach ($marcas as $marca) : ?>

                                                                            <option value=<?= $marca['id']; ?>> <?= $marca['nombre']; ?> </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <p  style="color: rgb(232,74,103)"> <?= session('marca'); ?></p>
                                                                </div>
                                                                <label  class="col-md-3 col-form-label">Modelo</label>
                                                                <div class="col">
                                                                        <select class="form-control" name="modelo" id="modeloBox" >
                                                                            <option disabled selected=inicial>Seleccione un Modelo:</option>

                                                                        </select>
                                                                        <p  style="color: rgb(232,74,103)" > <?= session('modelo'); ?></p>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Calcelar</button>
                                                                <button type="submit" class="btn btn-outline-primary">Registrar</button>
                                                            </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                                
                        </li>

                        <li class="list-group-item">
                        
                            <button class="btn btn-flat mb-1 btn-primary" type="button" data-toggle="collapse" data-target="#desplegable" aria-expanded="false" aria-controls="desplegable">Estadia definida</button>
                            
                            <div class="collapse" id="desplegable">
                             
                                
                                
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Cantidad de horas</label>
                                    <div class="col">
                                        <input type="text" name="cantidad_horas" class="form-control" placeholder="Cantidad de horas" >
                                        
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li class="list-group-item">
                        
                            <button class="btn btn-flat mb-1 btn-primary" type="button" data-toggle="collapse" data-target="#desplegablePago" aria-expanded="false" aria-controls="desplegable">Pagar estadia</button>
                            
                            <div class="collapse" id="desplegablePago">
                             
                                
                                
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Pagar</label>
                                    <div class="col">
                                        <p>Ver como se pagaria</p>
                                        
                                    </div>
                                </div>
                            </div>

                        </li>
                        
                    </ul>
                    
                    <div class="card-footer" id="div1">
                    <p style="color: rgb(232,74,103, 1)"> <?= session('mensaje'); ?></p>
                        <div class="row">
                        
                            <div class="col text-center">
                                <button type="submit" class="btn btn-flat mb-1 btn-primary">Comenzar estadia</button>
                                
                                <a href="#" class="btn btn-flat mb-1 btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- EOF Horizontal Form -->
    </div>


    <!-- EOF MAIN-BODY -->

</div>

<?= $this->endSection() ?>