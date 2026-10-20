<?php
require_once __DIR__ . '/../models/Funcion.php';

class funcioncontrolador
{
    private Funcion $modelo;

    public function __construct()
    {
        $this->modelo = new Funcion();
    }

    public function listFuncionVista(array $datos = []): array
    {
        $codigo = trim((string)($datos['codigo_buscar'] ?? ''));
        if ($codigo !== '') {
            $funcion = $this->modelo->buscarPorCodigo($codigo);
            return ['funciones'=>$funcion ? [$funcion] : [], 'codigo_buscar'=>$codigo, 'busqueda_realizada'=>true];
        }
        return ['funciones'=>$this->modelo->listar(), 'codigo_buscar'=>'', 'busqueda_realizada'=>false];
    }

    public function addFuncionVista(array $datos = []): array
    {
        return ['salas'=>$this->modelo->listarSalas(), 'peliculas'=>$this->modelo->listarPeliculas(), 'errores'=>[], 'datos'=>['codigo'=>'','id_sala'=>'','id_pelicula'=>'','fecha_hora'=>'']];
    }

    public function crearFuncion(array $datos): array
    {
        $formulario = ['codigo'=>trim((string)($datos['codigo'] ?? '')), 'id_sala'=>trim((string)($datos['id_sala'] ?? '')), 'id_pelicula'=>trim((string)($datos['id_pelicula'] ?? '')), 'fecha_hora'=>trim((string)($datos['fecha_hora'] ?? ''))];
        $errores = [];
        if ($formulario['codigo'] === '') {
            $errores['codigo'] = 'El código de la función es obligatorio.';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $formulario['codigo']) || strlen($formulario['codigo']) > 30) {
            $errores['codigo'] = 'Use un código de hasta 30 caracteres, solo letras y números.';
        } elseif ($this->modelo->buscarPorCodigo($formulario['codigo'])) {
            $errores['codigo'] = 'Ya existe una función con ese código.';
        }
        $idSala = filter_var($formulario['id_sala'], FILTER_VALIDATE_INT);
        if (!$idSala || $idSala < 1 || !$this->modelo->existeSala((int)$idSala)) {
            $errores['id_sala'] = 'Seleccione una sala válida.';
        }
        $idPelicula = filter_var($formulario['id_pelicula'], FILTER_VALIDATE_INT);
        if (!$idPelicula || $idPelicula < 1 || !$this->modelo->existePelicula((int)$idPelicula)) {
            $errores['id_pelicula'] = 'Seleccione una película válida.';
        }
        $fecha = DateTime::createFromFormat('!Y-m-d\TH:i', $formulario['fecha_hora']);
        if (!$fecha || $fecha->format('Y-m-d\TH:i') !== $formulario['fecha_hora']) {
            $errores['fecha_hora'] = 'Ingrese una fecha y hora válidas.';
        }
        if (!$errores && $this->modelo->existeConflictoSala((int)$idSala, $fecha->format('Y-m-d H:i:s'))) {
            $errores['general'] = 'La sala ya tiene una función programada para esa fecha y hora.';
        }
        if (!$errores) {
            try {
                $this->modelo->crear($formulario['codigo'], (int)$idSala, (int)$idPelicula, $fecha->format('Y-m-d H:i:s'));
                $_SESSION['flash_funcion'] = ['tipo'=>'exito','mensaje'=>'La función se registró correctamente.'];
                header('Location: index.php?a=funlis');
                exit;
            } catch (PDOException $e) {
                $errores['general'] = (($e->errorInfo[1] ?? 0) === 1062) ? 'Ya existe una función con ese código.' : 'No fue posible guardar la función. Revise los datos e intente nuevamente.';
            }
        }
        return ['salas'=>$this->modelo->listarSalas(), 'peliculas'=>$this->modelo->listarPeliculas(), 'errores'=>$errores, 'datos'=>$formulario];
    }

    public function eliminarFuncion(array $datos): void
    {
        $id = filter_var($datos['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1 || !$this->modelo->buscarPorId((int)$id)) {
            $_SESSION['flash_funcion'] = ['tipo'=>'error','mensaje'=>'La función seleccionada no es válida.'];
        } else {
            try {
                $this->modelo->eliminar((int)$id);
                $_SESSION['flash_funcion'] = ['tipo'=>'exito','mensaje'=>'La función se eliminó correctamente.'];
            } catch (PDOException $e) {
                $_SESSION['flash_funcion'] = ['tipo'=>'error','mensaje'=>'No fue posible eliminar la función.'];
            }
        }
        header('Location: index.php?a=funlis');
        exit;
    }
}
