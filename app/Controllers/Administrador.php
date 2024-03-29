<?php

namespace App\Controllers;

use App\Models\CuentaModel;
use App\Models\DominioVehiculoModel;
use App\Models\EstadiaModel;
use App\Models\HistorialZonaModel;
use App\Models\InfraccionModel;
use App\Models\RecuerdameModel;
use App\Models\RolModel;
use App\Models\TarjetaDeCreditoModel;
use App\Models\UserModel;
use App\Models\VehiculoModel;
use App\Models\VentaModel;
use App\Models\ZonaModel;
use DateTime;

class Administrador extends BaseController
{

    //eliminar usuario:
    public function eliminar($id)
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $userModel = new UserModel();
        $usuario = $userModel->find($id);


        if ($usuario['id_rol'] === '4') {

            $this->eliminarCliente($usuario);

        } elseif ($usuario['id_rol'] === '2') {//es vendedor

            $this->eliminarVendedor($usuario);

        } else { //es administrador o inspector

            $this->eliminarDatosPersonales($usuario);

        }

        session()->setFlashdata('mensaje', 'Los datos se eliminaron con exito');
        return redirect()->to(base_url('administrador/listadoUsuarios'));

    }

    private function eliminarCliente($usuario)
    {

        $dominioModel = new DominioVehiculoModel();
        $dominios = $dominioModel->tieneVehiculos($usuario['id']);

        if (!empty($dominios)) {

            $infraccionesModel = new InfraccionModel();

            foreach ($dominios as $dominio) {

                $infracciones = $infraccionesModel->obtenerInfraccionesPorVehiculoId($dominio['id_vehiculo']);

                if (empty($infracciones)) { //si infracciones es vacio (true) entonces no tiene multas

                    $estadiasModel = new EstadiaModel();
                    $estadiasPendientePorVehiculo = $estadiasModel->verificarEstadiasPagoPendientePorDominio($dominio['id']);

                    if (empty($estadiasPendientePorVehiculo)) { // si estadias pendientes es vacio (true) entonces no tiene ninguna estadia sin pagar


                        $estadiasTotales = $estadiasModel->buscarPorUsuarioId($usuario['id']);

                        foreach ($estadiasTotales as $estadia) {

                            $ventaModel = new VentaModel();
                            $ventas = $ventaModel->obtenerPorEstadias($estadia['id']);

                            if (!empty($ventas)) {

                                $ventaModel->eliminarVentasPorEstadia($estadia['id']);

                            }
                            $estadiasModel->delete($estadia['id']);
                        }
                    } else {
                        //TIENE ESTADIAS PENDIENTES: NO SE PUEDE BORRAR
                        session()->setFlashdata('error', 'No se puede eliminar debido a que algun vehiculo del cliente tiene estadias sin pagar');
                        return redirect()->back();
                    }


                } else {

                    session()->setFlashdata('error', 'No se puede eliminar debido a que algun vehiculo del cliente tiene infracciones');
                    return redirect()->back();

                }

            }

            foreach ($dominios as $dominio) { //se hace otro foreach ya que solo se van a borrar los dominios y vehiculos si se cumplen las condiciones del foreach anterior (o sea si llego la ejecucion hasta aca)

                $idVehiculo = $dominio['id_vehiculo'];

                //VER SI LOS VEHICULOS DE LOS DOMINIOS ESTAN RELACIONADOS A OTROS CLIENTES (para borrarlos o no)

                $vehiculoModel = new VehiculoModel();

                $otrosDominiosDelVehiculo = $dominioModel->obtenerDominioPorIdVehiculo($dominio['id_vehiculo']);

                if (empty($otrosDominiosDelVehiculo)) { //si otros dominios es vacio (true) entonces no hay otro propietario del vehiculo

                    $dominioModel->delete($dominio['id']);
                    $vehiculoModel->delete($idVehiculo);

                } else {

                    $dominioModel->delete($dominio['id']);
                }

            }

        }

        //los datos personales se van a borrar solo si se completo lo anterior en caso de tener dominios o si no tiene dominios es lo unico q se borra
        $this->eliminarDatosPersonales($usuario);

    }

    private function eliminarVendedor($usuario)
    {
        $ventaModel = new VentaModel();
        $ventas = $ventaModel->obtenerPorVendedor($usuario['id']);

        if (!empty($ventas)) {
            foreach ($ventas as $venta) {
                $ventaModel->delete($venta['id']);
            }
        }

        $this->eliminarDatosPersonales($usuario);
    }

    private function eliminarDatosPersonales($usuario)
    {

        if ($usuario['id_rol'] === '4') {
            $tarjetasModel = new TarjetaDeCreditoModel();
            $tarjetas = $tarjetasModel->obtenerTarjetasPorUsuario($usuario['id']);

            if (!empty($tarjetas)) {
                foreach ($tarjetas as $tarjeta) {
                    $tarjetasModel->delete($tarjeta['id']);
                }
            }

            $cuentaModel = new CuentaModel();
            $cuentaModel->eliminarCuentaUsuario($usuario['id']);
        }

        $recuerdameSesionModel = new RecuerdameModel();
        $recuerdameSesionModel->eliminarSesionesDeUsuario($usuario['id']);

        $userModel = new UserModel();
        $userModel->delete($usuario['id']);

    }


    //guardar usuario:
    public function guardarModificaciones()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $datos['datos'] = $userModel->find($_POST['id']);


        $validacion = $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required|is_unique[usuarios.{id}]',
            'email' => 'required|is_unique[usuarios.{id}]',
            'fecha_de_nacimiento' => 'required|valid_date',

        ]);


        if ($validacion) {

            if (empty($_POST['id_rol'])) {
                $_POST['id_rol'] = $datos['datos']['id_rol'];
            }

            $formatoFecha = explode('-', $_POST['fecha_de_nacimiento']);
            if (strlen($formatoFecha[0]) == 2) {
                $_POST['fecha_de_nacimiento'] = DateTime::createFromFormat("d-m-Y", $_POST['fecha_de_nacimiento'])->format('Y-m-d');
            }

            $userModel->update($_POST['id'], $_POST);

            session()->set([
                'username' => $_POST['email'],
            ]);

            session()->setFlashdata('mensaje', 'Los datos se guardaron con exito');
            return redirect()->to(base_url('administrador/listadoUsuarios'));
        } else {

            $error = $this->validator->getErrors();
            session()->setFlashdata($error);
            return redirect()->back()->withInput();
        }
    }

    public function editar($id)
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $data['usuario'] = $userModel->obtenerUsuario($id);

        $rolModel = new RolModel();
        $data['roles'] = $rolModel->findAll();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        return view('viewAdministrador/viewMasterMod', $data);
    }

    public function buscarDNI()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        $data['usuarioInfo'] = $userModel->obtenerUsuarioDNI($_POST['dni']);
        if (empty($data['usuarioInfo'])) {

            $userModel = new UserModel();
            $data['usuarioInfo'] = $userModel->obtenerUsuarios();
            session()->setFlashdata('mensaje', 'No se encontraron resultados');
            return redirect()->to(base_url());

        } else {

        }
    }

    public function listadoUsuarios()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $data['usuarioInfo'] = $userModel->obtenerUsuarios();
        $rolModel = new RolModel();
        $data['roles'] = $rolModel->findAll();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));
        return view('viewAdministrador/viewMasterList', $data);
    }

    public function addUser()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $rolModel = new RolModel();
        $data['roles'] = $rolModel->findAll();

        $userModel = new UserModel();

        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        return view('viewAdministrador/viewMasterAdd', $data);
    }

    public function guardar()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $validacion = $this->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required|is_unique[usuarios.dni]',
            'email' => 'required|is_unique[usuarios.email]',
            'id_rol' => 'required',
            'fecha_de_nacimiento' => 'required|valid_date',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validacion && ($_POST['password'] === $_POST['confirm_password'])) {

            $_POST['fecha_de_nacimiento'] = DateTime::createFromFormat("d-m-Y", $_POST['fecha_de_nacimiento'])->format('Y-m-d');

            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $userModel = new UserModel();
            $userModel->save($_POST);

            if ($_POST['id_rol'] == 4) {
                $cuentaModel = new CuentaModel();
                $cuentainfo = [
                    'monto' => '0',
                    'id_usuario' => $userModel->obtenerUsuarioEmail($_POST['email'])['id'],
                ];
                $cuentaModel->save($cuentainfo);
            }

            session()->setFlashdata('mensaje', 'Los datos se guardaron con exito');
            return redirect()->to(base_url('home/index'));
        } else {


            $error = $this->validator->getErrors();
            if (($_POST['password'] !== $_POST['confirm_password']))
                $error['confirm_password1'] = 'Las Contraseñas deben coincidir';
            session()->setFlashdata($error);
            return redirect()->back()->withInput();
        }
    }

    private function esAdministrador()
    {
        if (session('rol') === '1') {
            return true;
        }
        return false;
    }

    public function verListadoVehiculosEstacionados()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $userModel = new UserModel();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        $estadiaModel = new EstadiaModel();

        $data['estadias_activas'] = $estadiaModel->estadiasActivas();
        $cantidadDeHoras[] = null;
        $i = 0;
        foreach ($data['estadias_activas'] as $infoEstadia) {
            $cantidadDeHoras[$i] = $this->calcularHoras($infoEstadia['fecha_inicio'], $infoEstadia['fecha_fin']);
            $i++;
        }

        $data['cantidad_horas'] = $cantidadDeHoras;

        return view('viewAdministrador/viewMasterListadoVehiculosEstacionados', $data);
    }

    public function verRestablecerPassword($id)
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $data['usuario'] = $userModel->obtenerUsuario($id);

        $rolModel = new RolModel();
        $data['roles'] = $rolModel->findAll();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        return view('viewAdministrador/viewMasterRestablecerPassword', $data);
    }

    public function restablecerPassword()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $userModel = new UserModel();
        $datos['datos'] = $userModel->find($_POST['id']);


        $validacion = $this->validate([
            'password' => 'required',
            'confirm_password' => 'required'
        ]);


        if ($validacion && ($_POST['password'] === $_POST['confirm_password'])) {

            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $userModel->update($_POST['id'], $_POST);

            session()->setFlashdata('mensaje', 'Los datos se guardaron con exito');
            return redirect()->to(base_url('administrador/listadoUsuarios'));

        } else {

            $error = $this->validator->getErrors();
            if (($_POST['password'] !== $_POST['confirm_password']))
                $error['confirm_password1'] = 'Las Contraseñas deben coincidir';

            session()->setFlashdata($error);
            return redirect()->back()->withInput();
        }
    }

    private function calcularHoras($fecha_inicio, $fecha_fin): string
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }
        $fechaDeInicio = new DateTime($fecha_inicio);
        $fechaDeFin = new DateTime($fecha_fin);

        $diferenciaDeHoras = $fechaDeInicio->diff($fechaDeFin);
        $hora = $diferenciaDeHoras->h . ':' . $diferenciaDeHoras->i . ':' . $diferenciaDeHoras->s;

        return $hora;
    }

    public function verListadoInfracciones()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $userModel = new UserModel();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));

        $infraccionesModel = new InfraccionModel();
        $data['infracciones'] = $infraccionesModel->obtenerTodos();

        return view('viewAdministrador/viewMasterListadoInfracciones', $data);
    }

    public function verModificarCostoZona()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $userModel = new UserModel();
        $zonaModel = new ZonaModel();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));
        $data['zonas'] = $zonaModel->findAll();


        return view('viewAdministrador/viewMasterModificarCostoZona', $data);
    }

    public function verModificarHorarioZona()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $userModel = new UserModel();
        $zonaModel = new ZonaModel();
        $data['usuarioActual'] = $userModel->obtenerUsuarioEmail(session()->get('username'));
        $data['zonas'] = $zonaModel->findAll();

        return view('viewAdministrador/viewMasterModificarHorarioZona', $data);
    }

    public function obtenerHistoralZona($idZona)
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $historalZonaModel = new HistorialZonaModel();
        return json_encode($historalZonaModel->obtenerZonas($idZona));

    }

    public function modificarHorarioZona()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $validacion = $this->validate([
            'id_zona' => 'required',
            'historial_zona' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',

        ]);
        if ($validacion) {
            $historialZonaModel = new HistorialZonaModel();

            $historialZonaSigTurno = $historialZonaModel->obtenerSiguienteTurno($_POST['id_zona'], $_POST['historial_zona']);
            $hZonaActual = $historialZonaModel->find($_POST['historial_zona']);

            if (!$this->verificarTurno($hZonaActual, $historialZonaSigTurno, $_POST['hora_inicio'], $_POST['hora_fin'])) {
                return redirect()->back()->with('errorDeHora',
                    'La hora de inicio tiene que ser mayor a la hora de finalizacion y la hora actual. Los horarios de los turnos no se pueden superponer')
                    ->withInput();
            }

            $estado = [
                'estado' => false
            ];
            $historialZonaModel->update($_POST['historial_zona'], $estado);
            $data = [
                'comienzo' => $_POST['hora_inicio'],
                'final' => $_POST['hora_fin'],
                'precio' => $hZonaActual['precio'],
                'estado' => true,
                'id_zona' => $hZonaActual['id_zona']
            ];
            $historialZonaModel->insert($data);
            session()->setFlashdata('mensaje', 'Los datos se guardaron con exito');

            return redirect()->to(base_url('/home'));
        } else {

            $error = $this->validator->getErrors();
            session()->setFlashdata($error);
            return redirect()->back();
        }

    }

    private function verificarTurno($hZonaActual, $hZonaSiguiente, $inicio, $fin)
    {
        $horaFinHZActual = explode(':', $hZonaActual['final']);


        $comienzo = explode(':', $inicio);
        $final = explode(':', $fin);

        $fechaInicio = (new DateTime())->setTime($comienzo[0], $comienzo[1])->format('Y-m-d H:i:s');
        $fechaFin = (new DateTime())->setTime($final[0], $final[1])->format('Y-m-d H:i:s');

        if (!empty($hZonaSiguiente)) {
            $horaInicioHZSiguiente = explode(':', $hZonaSiguiente['comienzo']);
            $horaFinHZSiguiente = explode(':', $hZonaSiguiente['final']);

            $fechaHoraFinActual = (new DateTime())->setTime($horaFinHZActual[0], $horaFinHZActual[1])->format('Y-m-d H:i:s');
            $fechaHoraInicioHZSiguiente = (new DateTime())->setTime($horaInicioHZSiguiente[0], $horaInicioHZSiguiente[1])->format('Y-m-d H:i:s');

            if ($fechaHoraFinActual < $fechaHoraInicioHZSiguiente) {

                $fechaInicioSigTurno = (new DateTime())->setTime($horaInicioHZSiguiente[0], $horaInicioHZSiguiente[1])->format('Y-m-d H:i:s');

                if (($fechaInicio < $fechaFin) &&
                    ($fechaFin < $fechaInicioSigTurno) &&
                    (strftime('%A') != 'Saturday') &&
                    (strftime('%A') != 'Sunday')) {

                    return true;
                }

                return false;
            } else {
                $fechaFinTurnoAnterior = (new DateTime())->setTime($horaFinHZSiguiente[0], $horaFinHZSiguiente[1])->format('Y-m-d H:i:s');
                if (($fechaInicio < $fechaFin) &&
                    ($fechaInicio > $fechaFinTurnoAnterior) &&
                    (strftime('%A') != 'Saturday') &&
                    (strftime('%A') != 'Sunday')) {
                    return true;
                }
            }
            return false;
        } else {
            if (($fechaInicio < $fechaFin) &&
                (strftime('%A') != 'Saturday') &&
                (strftime('%A') != 'Sunday')) {
                return true;
            }
            return false;
        }
    }

    public function modificarPrecioZona()
    {
        if (!$this->esAdministrador()) {
            return redirect()->to(base_url());
        }

        $validacion = $this->validate([
            'id_zona' => 'required',
            'historial_zona' => 'required',
            'precio' => 'required',

        ]);
        if ($validacion) {
            $historialZonaModel = new HistorialZonaModel();

            $hZonaActual = $historialZonaModel->find($_POST['historial_zona']);
            $estado = [
                'estado' => false
            ];
            $historialZonaModel->update($_POST['historial_zona'], $estado);
            $data = [
                'comienzo' => $hZonaActual['comienzo'],
                'final' => $hZonaActual['final'],
                'precio' => $_POST['precio'],
                'estado' => true,
                'id_zona' => $hZonaActual['id_zona']
            ];
            $historialZonaModel->insert($data);
            session()->setFlashdata('mensaje', 'Los datos se guardaron con exito');

            return redirect()->to(base_url('/home'));
        } else
            $error = $this->validator->getErrors();

        session()->setFlashdata($error);
        return redirect()->back();
    }
}
