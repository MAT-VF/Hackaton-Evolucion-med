<?php
session_start();
include '../conexion.php';

// Validación básica
$required = ['id_proveedor','fecha_compra','total_compra','id_producto','cantidad','precio'];
foreach ($required as $k) {
  if (!isset($_POST[$k])) {
    header('Location: compras.php');
    exit;
  }
}

$id_proveedor = (int)$_POST['id_proveedor'];
$fecha_compra = $_POST['fecha_compra'];
$total = (float)$_POST['total_compra'];

// id_usuario real desde sesión
$id_usuario = (int)($_SESSION['id_usuario'] ?? 0);
if ($id_usuario <= 0) {
  header('Location: compras.php');
  exit;
}

$id_productos = $_POST['id_producto'];
$cantidades   = $_POST['cantidad'];
$precios       = $_POST['precio'];

// Normalizar arrays
if (!is_array($id_productos) || !is_array($cantidades) || !is_array($precios) || count($id_productos) === 0) {
  header('Location: compras.php');
  exit;
}

// Transacción
mysqli_begin_transaction($conn);
try {
  // 1) Insert compra
  $stmtCompra = mysqli_prepare(
    $conn,
    "INSERT INTO compras (fecha_compra, total, id_usuario, id_proveedor, estado_compra) VALUES (?, ?, ?, ?, 'COMPLETADA')"
  );
  if (!$stmtCompra) {
    throw new Exception('No se pudo preparar INSERT compras');
  }

  mysqli_stmt_bind_param($stmtCompra, 'sddi', $fecha_compra, $total, $id_usuario, $id_proveedor);
  mysqli_stmt_execute($stmtCompra);
  $id_compra = (int)mysqli_insert_id($conn);

  // 2) Sentencias reusables
  $stmtDetalle = mysqli_prepare(
    $conn,
    "INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?)"
  );
  $stmtProducto = mysqli_prepare(
    $conn,
    "UPDATE productos SET stock = stock + ? WHERE id_producto = ?"
  );
  $stmtMov = mysqli_prepare(
    $conn,
    "INSERT INTO movimientos_inventario (tipo_movimiento, cantidad, fecha, id_producto, observacion) VALUES ('ENTRADA', ?, NOW(), ?, ?)"
  );

  if (!$stmtDetalle || !$stmtProducto || !$stmtMov) {
    throw new Exception('No se pudieron preparar sentencias (detalle/stock/movimiento)');
  }

  $observacion = 'Ingreso por compra';

  // 3) Insertar detalle, actualizar stock y registrar movimiento
  for ($i = 0; $i < count($id_productos); $i++) {
    $id_producto = (int)($id_productos[$i] ?? 0);
    $cantidad    = (int)($cantidades[$i] ?? 0);
    $precio      = (float)($precios[$i] ?? 0);

    if ($id_producto <= 0 || $cantidad <= 0) {
      continue;
    }

    $subtotal = $cantidad * $precio;

    // detalle_compra
    mysqli_stmt_bind_param($stmtDetalle, 'iiidd', $id_compra, $id_producto, $cantidad, $precio, $subtotal);
    mysqli_stmt_execute($stmtDetalle);

    // update productos (fix del bug: ahora usa ? en WHERE)
    mysqli_stmt_bind_param($stmtProducto, 'ii', $cantidad, $id_producto);
    mysqli_stmt_execute($stmtProducto);

    // movimientos_inventario
    mysqli_stmt_bind_param($stmtMov, 'iis', $cantidad, $id_producto, $observacion);
    mysqli_stmt_execute($stmtMov);
  }

  mysqli_commit($conn);
  header('Location: compras.php');
  exit;

} catch (Throwable $e) {
  mysqli_rollback($conn);
  header('Location: compras.php');
  exit;
}
?>

