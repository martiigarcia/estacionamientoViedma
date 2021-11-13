<?= $this->extend("templates/vendedor/masterVendedor")?>
<?= $this->section('content') ?>

    vista grafica del vender
    <p>Id del dominio del vehiculo seleccionado: <?=$dominio->id?></p>

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
                                             <p>Vehiculo seleccionado: <?= $dominio->vehiculo_patente?> --> <?= $dominio->vehiculo_marca_nombre?>, <?= $dominio->vehiculo_modelo_nombre?></p>
                                        </div>
                                            <label>Seleccione una zona</label>
                                            <select class="form-control" name="id_zona" >
                                                
                                                <option disabled selected=inicial>Zonas:</option>
                                                <?php foreach ($zonas as $zona) : ?>

                                                    <option value=<?= $zona['id']; ?>><?= $zona['nombre']; ?>, <?= $zona['descripcion']; ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
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