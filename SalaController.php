<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Sala.php';

class SalaController extends Controller
{
    private ?Sala $sala = null;

    private function modelo(): Sala
    {
        if ($this->sala === null) {
            $this->sala = new Sala();
        }
        return $this->sala;
    }

    public function index(): void
    {
        $this->render('salas/index', [
            'salas' => $this->modelo()->listar(),
        ]);
    }

    public function crear(): void
    {
        $this->render('salas/crear', [
            'datos'   => ['nombre' => '', 'codigo' => '', 'capacidad' => ''],
            'errores' => [],
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=sala&a=crear');
            return;
        }

        $datos = [
            'nombre'    => trim($_POST['nombre'] ?? ''),
            'codigo'    => trim($_POST['codigo'] ?? ''),
            'capacidad' => trim($_POST['capacidad'] ?? ''),
        ];

        $errores = $this->validar($datos);

        if (empty($errores)) {
            try {
                $this->modelo()->crear($datos['nombre'], $datos['codigo'], (int) $datos['capacidad']);
                $this->flash('exito', 'La sala se creó correctamente.');
                $this->redirect('?c=sala&a=index');
                return;
            } catch (PDOException $e) {
                if (($e->errorInfo[1] ?? 0) === 1062) {
                    $errores['codigo'] = 'Ya existe una sala con ese código.';
                } else {
                    $errores['general'] = 'No fue posible guardar la sala. Intente de nuevo.';
                }
            }
        }

        $this->render('salas/crear', [
            'datos'   => $datos,
            'errores' => $errores,
        ]);
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?c=sala&a=index');
            return;
        }

        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            $this->flash('error', 'La sala indicada no es válida.');
            $this->redirect('?c=sala&a=index');
            return;
        }

        $sala = $this->modelo()->buscarPorId($id);
        if ($sala === null) {
            $this->flash('error', 'La sala que intenta eliminar no existe.');
            $this->redirect('?c=sala&a=index');
            return;
        }

        $cantidad = $this->modelo()->contarFunciones($id);
        if ($cantidad > 0) {
            $this->flash(
                'error',
                'No se puede eliminar la sala "' . $sala['nombre'] . '" porque tiene ' . $cantidad . ' función(es) asociada(s).'
            );
            $this->redirect('?c=sala&a=index');
            return;
        }

        try {
            $this->modelo()->eliminar($id);
            $this->flash('exito', 'La sala se eliminó correctamente.');
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1451) {
                $this->flash('error', 'No se puede eliminar la sala porque tiene funciones asociadas.');
            } else {
                $this->flash('error', 'No fue posible eliminar la sala. Intente de nuevo.');
            }
        }

        $this->redirect('?c=sala&a=index');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        if ($datos['nombre'] === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (!preg_match('/^[\p{L}\p{N} ]+$/u', $datos['nombre'])) {
            $errores['nombre'] = 'El nombre solo puede tener letras, números y espacios.';
        } elseif (mb_strlen($datos['nombre']) > 100) {
            $errores['nombre'] = 'El nombre no puede superar los 100 caracteres.';
        }

        if ($datos['codigo'] === '') {
            $errores['codigo'] = 'El código es obligatorio.';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $datos['codigo'])) {
            $errores['codigo'] = 'El código solo puede tener letras y números (sin espacios).';
        } elseif (strlen($datos['codigo']) > 20) {
            $errores['codigo'] = 'El código no puede superar los 20 caracteres.';
        } elseif ($this->modelo()->buscarPorCodigo($datos['codigo']) !== null) {
            $errores['codigo'] = 'Ya existe una sala con ese código.';
        }

        if ($datos['capacidad'] === '') {
            $errores['capacidad'] = 'La capacidad es obligatoria.';
        } elseif (!ctype_digit($datos['capacidad']) || (int) $datos['capacidad'] < 1) {
            $errores['capacidad'] = 'La capacidad debe ser un número entero mayor que 0.';
        } elseif ((int) $datos['capacidad'] > 100000) {
            $errores['capacidad'] = 'La capacidad ingresada es demasiado grande.';
        }

        return $errores;
    }
}
