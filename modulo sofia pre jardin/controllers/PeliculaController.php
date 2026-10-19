<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Pelicula.php';

class PeliculaController extends Controller
{
    private ?Pelicula $pelicula = null;

    private function modelo(): Pelicula
    {
        if ($this->pelicula === null) {
            $this->pelicula = new Pelicula();
        }
        return $this->pelicula;
    }

    public function index(): void
    {
        $this->render('peliculas/index', [
            'peliculas' => $this->modelo()->listar(),
        ]);
    }

    public function crear(): void
    {
        $this->render('peliculas/crear', [
            'datos'           => ['nombre' => '', 'codigo' => '', 'clasificacion' => ''],
            'errores'         => [],
            'clasificaciones' => Pelicula::CLASIFICACIONES,
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=pelicula&a=crear');
            return;
        }

        $datos = [
            'nombre'        => trim($_POST['nombre'] ?? ''),
            'codigo'        => trim($_POST['codigo'] ?? ''),
            'clasificacion' => trim($_POST['clasificacion'] ?? ''),
        ];

        $errores = $this->validar($datos);

        if (empty($errores)) {
            try {
                $this->modelo()->crear($datos['nombre'], $datos['codigo'], $datos['clasificacion']);
                $this->flash('exito', 'La película se creó correctamente.');
                $this->redirect('?c=pelicula&a=index');
                return;
            } catch (PDOException $e) {
                if (($e->errorInfo[1] ?? 0) === 1062) {
                    $errores['codigo'] = 'Ya existe una película con ese código.';
                } else {
                    $errores['general'] = 'No fue posible guardar la película. Intente de nuevo.';
                }
            }
        }

        $this->render('peliculas/crear', [
            'datos'           => $datos,
            'errores'         => $errores,
            'clasificaciones' => Pelicula::CLASIFICACIONES,
        ]);
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=pelicula&a=index');
            return;
        }

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            $this->flash('error', 'La película indicada no es válida.');
            $this->redirect('?c=pelicula&a=index');
            return;
        }

        $pelicula = $this->modelo()->buscarPorId($id);
        if ($pelicula === null) {
            $this->flash('error', 'La película que intenta eliminar no existe.');
            $this->redirect('?c=pelicula&a=index');
            return;
        }

        $cantidad = $this->modelo()->contarFunciones($id);
        if ($cantidad > 0) {
            $this->flash(
                'error',
                'No se puede eliminar la película "' . $pelicula['nombre'] . '" porque tiene ' . $cantidad . ' función(es) asociada(s).'
            );
            $this->redirect('?c=pelicula&a=index');
            return;
        }

        try {
            $this->modelo()->eliminar($id);
            $this->flash('exito', 'La película se eliminó correctamente.');
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1451) {
                $this->flash('error', 'No se puede eliminar la película porque tiene funciones asociadas.');
            } else {
                $this->flash('error', 'No fue posible eliminar la película. Intente de nuevo.');
            }
        }

        $this->redirect('?c=pelicula&a=index');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        if ($datos['nombre'] === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (!preg_match('/^[\p{L}\p{N} ]+$/u', $datos['nombre'])) {
            $errores['nombre'] = 'El nombre solo puede tener letras, números y espacios.';
        } elseif (mb_strlen($datos['nombre']) > 150) {
            $errores['nombre'] = 'El nombre no puede superar los 150 caracteres.';
        }

        if ($datos['codigo'] === '') {
            $errores['codigo'] = 'El código es obligatorio.';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $datos['codigo'])) {
            $errores['codigo'] = 'El código solo puede tener letras y números (sin espacios).';
        } elseif (strlen($datos['codigo']) > 20) {
            $errores['codigo'] = 'El código no puede superar los 20 caracteres.';
        } elseif ($this->modelo()->buscarPorCodigo($datos['codigo']) !== null) {
            $errores['codigo'] = 'Ya existe una película con ese código.';
        }

        if ($datos['clasificacion'] === '') {
            $errores['clasificacion'] = 'Debe seleccionar una clasificación.';
        } elseif (!in_array($datos['clasificacion'], Pelicula::CLASIFICACIONES, true)) {
            $errores['clasificacion'] = 'La clasificación seleccionada no es válida.';
        }

        return $errores;
    }
}
