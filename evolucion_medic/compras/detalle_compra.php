<?php
require_once '../core/guard.php';
requireLogin();
include '../conexion.php';

$id_compra = (int)($_GET['id_compra'] ?? 0);

if ($id_compra <= 0) {
  header('Location: compras.php');
  exit;
}

$stmt = mysqli_prepare($conn,
  "SELECT c.*, u.nombre AS usuario_nombre, p.nombre_empresa AS proveedor_nombre
   FROM compras c
   LEFT JOIN usuarios u ON u.id_usuario=c.id_usuario
   LEFT JOIN proveedores p ON p.id_proveedor=c.id_proveedor
   WHERE c.id_compra=?"
);
mysqli_stmt_bind_param($stmt,'i',$id_compra);
mysqli_stmt_execute($stmt);
$compra = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$detalles = mysqli_query($conn,
  "SELECT d.*, pr.nombre
   FROM detalle_compra d
   LEFT JOIN productos pr ON pr.id_producto=d.id_producto
   WHERE d.id_compra=".(int)$id_compra
);

include '../core/layout.php';
renderPageStart('Detalle de compra','compras');
?>

<div class="page-container">
  <div class="panel-dark">
    <h4 class="text-info" style="font-weight:900;margin-bottom:8px;">Detalle de Compra #<?php echo (int)$id_compra; ?></h4>
    <div class="text-muted" style="opacity:.85;">
      Fecha: <?php echo htmlspecialchars($compra['fecha_compra'] ?? ''); ?> |
      Proveedor: <?php echo htmlspecialchars($compra['proveedor_nombre'] ?? ''); ?> |
      Total: Bs. <?php echo number_format((float)($compra['total'] ?? 0),2); ?>
    </div>

    <hr style="border-color:rgba(255,255,255,.08);">

    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php while($d = mysqli_fetch_assoc($detalles)) { ?>
            <tr>
              <td style="font-weight:800;"><?php echo htmlspecialchars($d['nombre']); ?></td>
              <td><?php echo (int)$d['cantidad']; ?></td>
              <td>Bs. <?php echo number_format((float)$d['precio'],2); ?></td>
              <td>Bs. <?php echo number_format((float)$d['subtotal'],2); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      <a href="compras.php" class="btn" style="background:rgba(255,255,255,.06);color:var(--text);border:1px solid rgba(255,255,255,.08);border-radius:14px;">Volver</a>
    </div>
  </div>
</div>

<?php renderPageEnd(); ?>

