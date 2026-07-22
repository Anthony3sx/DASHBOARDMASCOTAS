<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/funciones/limpieza.php';
require_once __DIR__ . '/clases/Conexion.php';
require_once __DIR__ . '/clases/MascotaRepositorio.php';

$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
$mensaje = $_SESSION['mensaje'] ?? null;
$datos = $_SESSION['datos_formulario'] ?? [];
unset($_SESSION['mensaje']);
$mascotas = [];
$errorListado = null;
try { $mascotas = (new MascotaRepositorio())->listar(); }
catch (RuntimeException $e) { $errorListado = $e->getMessage(); }
function valor(string $campo, array $datos): string { return escapar((string) ($datos[$campo] ?? '')); }
$menus = [
  'Mascotas' => [['Lista de Mascotas','lista'],['Registro de Pacientes','registro'],['Actualizar Datos de Mascota','lista']],
  'Cirugías' => [['Calendario de Cirugías','proximamente'],['Programar Cirugía','proximamente'],['Seguimiento Postoperatorio','proximamente']],
  'Vacunación' => [['Historial de Vacunación','proximamente'],['Registrar Nueva Vacuna','proximamente'],['Próximas Vacunas','proximamente']],
  'Farmacia' => [['Inventario de Farmacia','proximamente'],['Entrada de Medicamentos','proximamente'],['Medicamentos por Vencer','proximamente']],
  'Facturación' => [['Nueva Factura','proximamente'],['Historial de Facturas','proximamente'],['Pagos Pendientes','proximamente']],
  'Configuración' => [['Datos de la Clínica','proximamente'],['Usuarios del Sistema','proximamente'],['Cerrar Sesión','proximamente']],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Dashboard administrativo veterinario">
  <title>Dashboard Veterinario | Huellas</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body class="dashboard-body">
<aside class="w3-sidebar w3-bar-block w3-card sidebar" id="sidebar">
  <section class="w3-card identidad">
    <button class="cerrar-menu" type="button" aria-label="Cerrar menú" onclick="cerrarMenu()">×</button>
    <div class="logo-vet" aria-hidden="true">H</div>
    <div><strong>Clínica Huellas</strong><span>Sistema Veterinario</span></div>
    <div class="administrador"><b>Administrador</b><small>María Fernández</small></div>
  </section>
  <nav aria-label="Navegación administrativa">
    <?php foreach ($menus as $menu => $submenus): $id = 'menu-' . md5($menu); ?>
      <button class="w3-bar-item w3-button w3-hover-blue menu-principal" type="button" aria-expanded="<?= $menu === 'Mascotas' ? 'true' : 'false' ?>" aria-controls="<?= $id ?>">
        <span><?= escapar($menu) ?></span><b>⌄</b>
      </button>
      <div class="w3-bar-block w3-card submenu <?= $menu === 'Mascotas' ? 'abierto' : '' ?>" id="<?= $id ?>">
        <?php foreach ($submenus as [$texto,$destino]): ?>
          <a class="w3-bar-item w3-button w3-hover-blue" href="#<?= $destino ?>"><?= escapar($texto) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </nav>
</aside>
<div class="w3-overlay" id="overlay" onclick="cerrarMenu()"></div>

<main class="contenido-dashboard">
  <header class="cabecera-dashboard">
    <button class="boton-menu" type="button" onclick="abrirMenu()" aria-label="Abrir menú">☰</button>
    <div><p>Panel administrativo</p><h1>Registro de Pacientes</h1></div>
    <span class="fecha"><?= date('d/m/Y') ?></span>
  </header>

  <?php if ($mensaje): ?>
    <div class="alerta <?= $mensaje['tipo'] === 'exito' ? 'exito' : 'error' ?>" role="alert">
      <strong><?= $mensaje['tipo'] === 'exito' ? 'Registro completado' : 'Revisa la información' ?></strong>
      <span><?= escapar($mensaje['texto']) ?></span><button type="button" aria-label="Cerrar">×</button>
    </div>
  <?php endif; ?>

  <section class="resumen w3-row-padding">
    <div class="w3-third"><article class="w3-card tarjeta-resumen"><span>Total de mascotas</span><strong><?= count($mascotas) ?></strong><small>Expedientes registrados</small></article></div>
    <div class="w3-third"><article class="w3-card tarjeta-resumen"><span>Módulo activo</span><strong>Mascotas</strong><small>Registro y seguimiento</small></article></div>
    <div class="w3-third"><article class="w3-card tarjeta-resumen"><span>Estado del sistema</span><strong class="activo">Disponible</strong><small>Conexión segura PDO</small></article></div>
  </section>

  <section class="w3-card formulario-dashboard" id="registro">
    <div class="titulo-seccion"><div><span>+</span><h2>Registro de Pacientes</h2></div><p>Complete la información de la mascota y su propietario.</p></div>
    <form action="procesar.php" method="post" id="formMascota" novalidate>
      <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
      <div class="w3-row-padding campos">
        <label class="w3-third">Nombre de la mascota<input class="w3-input w3-border" name="nombre" maxlength="100" value="<?= valor('nombre',$datos) ?>" required><small></small></label>
        <label class="w3-third">Especie<select class="w3-select w3-border" name="especie" required><option value="">Seleccione</option><?php foreach(['Perro','Gato','Ave','Conejo','Otro'] as $op): ?><option <?= ($datos['especie']??'')===$op?'selected':'' ?>><?= $op ?></option><?php endforeach; ?></select><small></small></label>
        <label class="w3-third">Raza<input class="w3-input w3-border" name="raza" maxlength="100" value="<?= valor('raza',$datos) ?>" required><small></small></label>
        <label class="w3-third">Edad (años)<input class="w3-input w3-border" type="number" name="edad" min="0" max="100" value="<?= valor('edad',$datos) ?>" required><small></small></label>
        <label class="w3-third">Peso actual (kg)<input class="w3-input w3-border" type="number" name="peso" min="0.01" step="0.01" value="<?= valor('peso',$datos) ?>" required><small></small></label>
        <label class="w3-third">Color o señas físicas<input class="w3-input w3-border" name="color_senas" maxlength="100" value="<?= valor('color_senas',$datos) ?>" required><small></small></label>
        <label class="w3-third">Nombre del propietario<input class="w3-input w3-border" name="responsable" maxlength="100" value="<?= valor('responsable',$datos) ?>" required><small></small></label>
        <label class="w3-third">DNI del propietario<input class="w3-input w3-border" name="dni" maxlength="15" value="<?= valor('dni',$datos) ?>" pattern="[0-9-]{13,15}" placeholder="0000-0000-00000" required><small></small></label>
        <label class="w3-third">Teléfono de emergencia<input class="w3-input w3-border" type="tel" name="telefono" maxlength="20" value="<?= valor('telefono',$datos) ?>" pattern="[0-9+() -]{8,20}" required><small></small></label>
        <label class="w3-col s12">Alergias / Observaciones<textarea class="w3-input w3-border w3-textarea" name="observaciones" maxlength="500" rows="4" placeholder="Describa alergias, tratamientos u observaciones importantes"><?= valor('observaciones',$datos) ?></textarea><small></small></label>
      </div>
      <div class="acciones-dashboard">
        <button class="w3-button w3-light-grey w3-hover-blue" type="reset">Limpiar información</button>
        <button class="w3-button w3-blue w3-hover-blue" type="submit">Guardar paciente</button>
      </div>
    </form>
  </section>

  <section class="w3-card lista-dashboard" id="lista">
    <div class="titulo-seccion"><div><span>≡</span><h2>Lista de Mascotas</h2></div><p>Pacientes registrados en el sistema.</p></div>
    <?php if ($errorListado): ?><div class="vacio error-listado"><strong>Base de datos no disponible</strong><p><?= escapar($errorListado) ?></p></div>
    <?php elseif (!$mascotas): ?><div class="vacio"><strong>Aún no hay pacientes registrados</strong><p>Use el formulario para guardar el primero.</p></div>
    <?php else: ?><div class="tabla-wrap"><table class="w3-table w3-striped w3-bordered"><thead><tr><th>ID</th><th>Mascota</th><th>Edad / Peso</th><th>Propietario</th><th>DNI</th><th>Observaciones</th></tr></thead><tbody>
      <?php foreach ($mascotas as $m): ?><tr><td>#<?= (int)$m['id'] ?></td><td><strong><?= escapar($m['nombre']) ?></strong><small><?= escapar($m['especie']) ?> · <?= escapar($m['raza']) ?></small></td><td><?= (int)$m['edad'] ?> años<small><?= number_format((float)$m['peso_actual'],2) ?> kg</small></td><td><?= escapar($m['responsable']) ?><small><?= escapar($m['telefono_emergencia']) ?></small></td><td><?= escapar($m['dni_propietario']) ?></td><td><?= escapar($m['observaciones'] ?: 'Sin observaciones') ?></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
  </section>

  <section class="w3-card modulo-proximamente" id="proximamente"><h2>Módulos administrativos</h2><p>Los accesos de Cirugías, Vacunación, Farmacia, Facturación y Configuración forman parte de la navegación visual solicitada y están preparados para futuras ampliaciones.</p></section>
</main>
<script src="assets/js/app.js"></script>
</body>
</html>
