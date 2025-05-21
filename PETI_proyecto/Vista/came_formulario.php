<?php
include_once '../Config/clsConexion.php';
include_once '../Models/CameModel.php';

$conexion = (new clsConexion())->getConexion();
$model = new CameModel($conexion);

$id_empresa = $_GET['id_empresa'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $acciones = $_POST['acciones'] ?? [];

    foreach ($acciones as $tipo => $items) {
        foreach ($items as $item) {
            $id_factor = $item['id_factor'] ?? null;
            $descripcion = trim($item['descripcion'] ?? '');

            if ($id_factor && $descripcion !== '') {
                $model->guardarAccionCAME($id_empresa, $tipo, $id_factor, $descripcion);
            }
        }
    }

    // echo "<p>¡Acciones guardadas correctamente!</p>";
}
// Obtener datos para mostrar el formulario
$debilidades = $model->obtenerFactoresPorEmpresa("tb_debilidades", $id_empresa);
$amenazas = $model->obtenerFactoresPorEmpresa("tb_amenazas", $id_empresa);
$fortalezas = $model->obtenerFactoresPorEmpresa("tb_fortalezas", $id_empresa);
$oportunidades = $model->obtenerFactoresPorEmpresa("tb_oportunidades", $id_empresa);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Matriz CAME</title>
  <link rel="stylesheet" href="../public/css/came.css">
</head>
<body>
<h2>Matriz CAME</h2>
<link rel="stylesheet" href="../public/css/came.css">
<form method="post" action="">
  <input type="hidden" name="id_empresa" value="<?= $id_empresa ?>">

  <h3>C - Corregir Debilidades</h3>
  <?php foreach ($debilidades as $index => $deb): ?>
    <p><strong><?= htmlspecialchars($deb['descripcion']) ?></strong></p>
    <textarea name="acciones[C][<?= $index ?>][descripcion]" required></textarea>
    <input type="hidden" name="acciones[C][<?= $index ?>][id_factor]" value="<?= $deb['id_debilidad'] ?>">
  <?php endforeach; ?>

  <h3>A - Afrontar Amenazas</h3>
  <?php foreach ($amenazas as $index => $ame): ?>
    <p><strong><?= htmlspecialchars($ame['descripcion']) ?></strong></p>
    <textarea name="acciones[A][<?= $index ?>][descripcion]" required></textarea>
    <input type="hidden" name="acciones[A][<?= $index ?>][id_factor]" value="<?= $ame['id_amenaza'] ?>">
  <?php endforeach; ?>

  <h3>M - Mantener Fortalezas</h3>
  <?php foreach ($fortalezas as $index => $fort): ?>
    <p><strong><?= htmlspecialchars($fort['descripcion']) ?></strong></p>
    <textarea name="acciones[M][<?= $index ?>][descripcion]" required></textarea>
    <input type="hidden" name="acciones[M][<?= $index ?>][id_factor]" value="<?= $fort['id_fortaleza'] ?>">
  <?php endforeach; ?>

  <h3>E - Explotar Oportunidades</h3>
  <?php foreach ($oportunidades as $index => $opu): ?>
    <p><strong><?= htmlspecialchars($opu['descripcion']) ?></strong></p>
    <textarea name="acciones[E][<?= $index ?>][descripcion]" required></textarea>
    <input type="hidden" name="acciones[E][<?= $index ?>][id_factor]" value="<?= $opu['id_oportunidad'] ?>">
  <?php endforeach; ?>

  <button type="submit">Guardar Todas las Acciones</button>
</form></body>
</html>