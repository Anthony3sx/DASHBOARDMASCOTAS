<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/funciones/limpieza.php';
require_once __DIR__ . '/clases/Conexion.php';
require_once __DIR__ . '/clases/Mascota.php';
require_once __DIR__ . '/clases/MascotaRepositorio.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    if (!isset($_POST['csrf'], $_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string) $_POST['csrf'])) {
        throw new RuntimeException('La sesión del formulario venció. Actualiza la página e inténtalo de nuevo.');
    }

    $campos = ['nombre', 'especie', 'raza', 'edad', 'peso', 'color_senas', 'responsable', 'telefono', 'dni', 'observaciones'];
    $datos = [];
    foreach ($campos as $campo) {
        $datos[$campo] = limpiarEntrada((string) ($_POST[$campo] ?? ''));
    }
    $_SESSION['datos_formulario'] = $datos;

    if ($datos['edad'] === '' || filter_var($datos['edad'], FILTER_VALIDATE_INT) === false) {
        throw new InvalidArgumentException('La edad debe ser un número entero válido.');
    }
    if ($datos['peso'] === '' || !is_numeric($datos['peso'])) {
        throw new InvalidArgumentException('El peso debe ser numérico y mayor que cero.');
    }

    $mascota = new Mascota(
        $datos['nombre'], $datos['especie'], $datos['raza'], (int) $datos['edad'],
        (float) $datos['peso'], $datos['color_senas'], $datos['responsable'], $datos['telefono'],
        $datos['dni'], $datos['observaciones']
    );

    (new MascotaRepositorio())->guardar($mascota);
    unset($_SESSION['datos_formulario']);
    $_SESSION['mensaje'] = ['tipo' => 'exito', 'texto' => 'Mascota registrada correctamente en el expediente maestro.'];
} catch (InvalidArgumentException | RuntimeException $e) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => $e->getMessage()];
} catch (Throwable $e) {
    error_log('Error inesperado: ' . $e->getMessage());
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Ocurrió un error inesperado. Inténtalo nuevamente.'];
}

header('Location: dashboard_vet.php#registro');
exit;
