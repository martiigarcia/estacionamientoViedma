<?= $this->extend("templates/master") ?>

<?= $this->section('titulo') ?>
    Registrar venta
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="main">

        <div class="row">

            <div class="col-lg-9">
                <div class="card mb-3">

                    <div class="card-header uppercase">
                        <div class="caption">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                                 fill="currentColor">
                                <path d="M0 0h24v24H0V0z" fill="none"/>
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z"/>
                                <circle cx="7.5" cy="14.5" r="1.5"/>
                                <circle cx="16.5" cy="14.5" r="1.5"/>
                            </svg>

                            Estacionar
                        </div>

                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="form-group row">


                                <div class="col">
                                    <div class="form-group">
                                        <input hidden="" name="dominio_vehiculo" id="dominio_vehiculo"
                                               value="<?= $dominio['id'] ?>">

                                    </div>
                                    <label>Seleccione una zona</label>
                                    <select class="form-control" name="id_zona" id="id_zona">

                                        <option disabled selected=inicial value="">Zonas:</option>
                                        <?php foreach ($zonas as $zona) : ?>

                                            <option value=<?= $zona['id']; ?>><?= $zona['nombre']; ?>
                                                , <?= $zona['descripcion']; ?>
                                            </option>

                                        <?php endforeach; ?>
                                    </select>
                                    <p style="color: rgb(232,74,103)"> <?= session('id_zona') ? 'Debe Completar este campo' : ''; ?></p>
                                </div>

                            </div>

                        </li>

                        <li class="list-group-item">
                            <p style="color: rgb(232,74,103)"> <?= session('errorDeCantidadDeHoras'); ?></p>
                            <button class="btn btn-flat mb-1 btn-primary" type="button" data-toggle="collapse"
                                    data-target="#desplegable" aria-expanded="false" aria-controls="desplegable">
                                Definir horario de finalizacion
                            </button>

                            <div class="collapse" id="desplegable">
                                 

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Hora de fin</label>
                                    <div class="col">
                                        <input type="time" name="cantidad_horas" id="cantidad_horas"
                                               class="form-control">

                                    </div>
                                </div>

                            </div>
                        </li>
                    </ul>

                    <div class="card-footer" id="div1">
                        <div class="row">

                            <div class="col text-center">
                                <button type="submit" class="btn btn-flat mb-1 btn-primary"
                                        onclick="return abonarVenta()">Comenzar estadia
                                </button>

                                <a href="<?= base_url('/home'); ?>"
                                   class="btn btn-flat mb-1 btn-secondary">Cancelar</a>
                            </div>
                        </div>
                        <p style="color: rgb(232,74,103)"> <?= session('errorHoraDeInicio'); ?></p>

                    </div>

                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>