<?php
require_once '../core/guard.php';
requireLogin();
include '../conexion.php';

// CRUD básico (crear)
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio_compra = (float)($_POST['precio_compra'] ?? 0);
    $precio_venta = (float)($_POST['precio_venta'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $stock_minimo = (int)($_POST['stock_minimo'] ?? 5);
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
    $codigo_barras = trim($_POST['codigo_barras'] ?? '');
    $lote = trim($_POST['lote'] ?? '');
    $id_categoria = (int)($_POST['id_categoria'] ?? 1);
    $id_proveedor = (int)($_POST['id_proveedor'] ?? 1);

    if ($nombre !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO productos (nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo, fecha_vencimiento, codigo_barras, lote, id_categoria, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $fecha_venc = $fecha_vencimiento ?: null;
        mysqli_stmt_bind_param($stmt, 'ssddiis ssii', $nombre, $descripcion, $precio_compra, $precio_venta, $stock, $stock_minimo, $fecha_venc, $codigo_barras, $lote, $id_categoria, $id_proveedor);
        mysqli_stmt_execute($stmt);
        $mensaje = 'Producto creado correctamente.';
    }
}

$productos = mysqli_query($conn, "SELECT p.*, prov.nombre_empresa FROM productos p LEFT JOIN proveedores prov ON prov.id_proveedor=p.id_proveedor ORDER BY p.id_producto DESC");
$proveedores = mysqli_query($conn, "SELECT id_proveedor, nombre_empresa FROM proveedores ORDER BY nombre_empresa");
$categorias = mysqli_query($conn, "SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria");

include '../core/layout.php';
renderPageStart('Productos', 'productos');
?>

<div class="page-container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h2 style="margin:0;font-weight:900;">Productos</h2>
      <div class="text-muted" style="opacity:.8;">Gestión de inventario maestro</div>
    </div>
  </div>

  <?php if($mensaje): ?>
    <div class="alert alert-success border-0" style="background:rgba(23,212,232,.12);color:var(--text);">
      <?php echo htmlspecialchars($mensaje); ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="panel-dark sticky-panel">
        <h4 class="text-info" style="font-weight:900; margin-bottom:16px;">Nuevo producto</h4>
        <form method="POST">
          <input type="hidden" name="accion" value="crear">

          <label class="form-label">Nombre</label>
          <input class="form-control mb-2" name="nombre" required>

          <label class="form-label">Descripción</label>
          <textarea class="form-control mb-2" name="descripcion" rows="3"></textarea>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Precio compra</label>
              <input class="form-control" type="number" step="0.01" name="precio_compra" value="0">
            </div>
            <div class="col-6">
              <label class="form-label">Precio venta</label>
              <input class="form-control" type="number" step="0.01" name="precio_venta" value="0">
            </div>
          </div>

          <div class="row g-2 mt-2">
            <div class="col-6">
              <label class="form-label">Stock</label>
              <input class="form-control" type="number" name="stock" value="0">
            </div>
            <div class="col-6">
              <label class="form-label">Stock mínimo</label>
              <input class="form-control" type="number" name="stock_minimo" value="5">
            </div>
          </div>

          <label class="form-label mt-2">Fecha vencimiento</label>
          <input class="form-control mb-2" type="date" name="fecha_vencimiento">

          <label class="form-label">Código barras</label>
          <input class="form-control mb-2" name="codigo_barras">

          <label class="form-label">Lote</label>
          <input class="form-control mb-2" name="lote">

          <label class="form-label">Categoría</label>
          <select class="form-select mb-2" name="id_categoria">
            <?php while($c = mysqli_fetch_assoc($categorias)) { ?>
              <option value="<?php echo (int)$c['id_categoria']; ?>"><?php echo htmlspecialchars($c['nombre_categoria']); ?></option>
            <?php } ?>
          </select>

          <label class="form-label">Proveedor</label>
          <select class="form-select mb-3" name="id_proveedor">
            <?php while($p = mysqli_fetch_assoc($proveedores)) { ?>
              <option value="<?php echo (int)$p['id_proveedor']; ?>"><?php echo htmlspecialchars($p['nombre_empresa']); ?></option>
            <?php } ?>
          </select>

          <button class="btn" type="submit" style="background:linear-gradient(90deg,var(--accent),var(--accent2));color:#fff;font-weight:900;border:none;border-radius:14px;">Crear</button>
        </form>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="panel-dark">
        <h4 class="text-info" style="font-weight:900; margin-bottom:16px;">Listado</h4>
        <div class="table-responsive">
          <table class="table table-dark table-hover align-middle">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Proveedor</th>
                <th>Compra</th>
                <th>Venta</th>
                <th>Stock</th>
              </tr>
            </thead>
            <tbody>
              <?php while($prod = mysqli_fetch_assoc($productos)) { 
                $stock = (int)$prod['stock'];
                $min = (int)$prod['stock_minimo'];
                $badge = $stock <= $min ? 'danger' : 'success';
              ?>
                <tr>
                  <td>
                    <div style="font-weight:800;"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                    <div style="font-size:12px;color:var(--muted);"><?php echo htmlspecialchars($prod['codigo_barras'] ?? ''); ?></div>
                  </td>
                  <td><?php echo htmlspecialchars($prod['nombre_empresa'] ?? '—'); ?></td>
                  <td>Bs. <?php echo number_format((float)$prod['precio_compra'],2); ?></td>
                  <td>Bs. <?php echo number_format((float)$prod['precio_venta'],2); ?></td>
                  <td>
                    <span class="badge bg-<?php echo $badge; ?>"><?php echo $stock; ?> </span>
                    <div style="font-size:12px;color:var(--muted);">mín: <?php echo $min; ?></div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php renderPageEnd(); ?>

