<!-- BOF ASIDE-LEFT -->

<div id="sidebar" class="sidebar" style="width: fit-content;">
    <div class="sidebar-content">
        <!-- sidebar-menu  -->
        <div class="sidebar-menu">
            <ul>


                <li class="etiqueta">

                    <div class="subcat">
                        <ul class="list-unstyled components">

                            <li>
                                <a href="<?= base_url('inspector/verConsultaEstacionamiento'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                         height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                                        <g>
                                            <rect fill="none" height="24" width="24"/>
                                            <path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M6,20V4h7v4h5v12H6z M11,19h2v-1h1 c0.55,0,1-0.45,1-1v-3c0-0.55-0.45-1-1-1h-3v-1h4v-2h-2V9h-2v1h-1c-0.55,0-1,0.45-1,1v3c0,0.55,0.45,1,1,1h3v1H9v2h2V19z"/>
                                        </g>
                                    </svg>
                                    Estadias registradas</a>
                            </li>

                            <li>


                                <a type="button"
                                   data-toggle="modal"
                                   data-target="#consultarEstadiaModalToolPop">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                         width="24px"
                                         fill="currentColor">
                                        <path d="M0 0h24v24H0V0z" fill="none"/>
                                        <path d="M18.92 5.01C18.72 4.42 18.16 4 17.5 4h-11c-.66 0-1.21.42-1.42 1.01L3 11v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 6h10.29l1.04 3H5.81l1.04-3zM19 16H5v-4.66l.12-.34h13.77l.11.34V16z"/>
                                        <circle cx="7.5" cy="13.5" r="1.5"/>
                                        <circle cx="16.5" cy="13.5" r="1.5"/>
                                    </svg>
                                    Consultar estado de vehiculo</a>


                                <div class="modal fade modal-dark show" id="consultarEstadiaModalToolPop"
                                     tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalToolPopTitle" style="display: none;"
                                     aria-hidden="true"
                                     aria-labelledby="exampleModalLabel" aria-modal="true"
                                     style="display: block;">

                                    <div class="modal-dialog modal-dialog-centered" role="document">

                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalToolPopTitle">
                                                    Seleccione la patente del vehiculo a detallar
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="input-group-prepend">

                                                    <select class="form-control" name="vehiculo"
                                                            id="vehiculo"
                                                            required>


                                                        <option disabled selected=inicial>Vehiculos registrados
                                                        </option>

                                                        <?php foreach ($vehiculos as $v) : ?>

                                                            <option value=<?= $v['id']; ?>>
                                                                <?= $v['patente']; ?>
                                                                --> <?= $v['vehiculo_marca_nombre']; ?>
                                                                , <?= $v['vehiculo_modelo_nombre']; ?>
                                                            </option>

                                                        <?php endforeach; ?>

                                                    </select>


                                                    <button type="submit"
                                                            class="btn btn-outline-lemon"
                                                            onclick="verEstadoEstadia()"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Buscar vehiculo">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             width="16"
                                                             height="16" fill="currentColor"
                                                             class="bi bi-search" viewBox="0 0 16 16">
                                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                        </svg>
                                                    </button>


                                                </div>

                                            </div>


                                            <div class="modal-footer" style="display: initial">
                                                <p style="color: rgb(255,0,0)"> <?= session('errorVehiculo'); ?></p>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </li>


                        </ul>
                    </div>
                </li>


            </ul>
            </li>
        </div>
        <!-- sidebar-menu  -->
    </div>
</div>
<!-- EOF ASIDE-LEFT -->
