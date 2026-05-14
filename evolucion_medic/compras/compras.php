<?php
require_once '../core/guard.php';
requireLogin();
include '../conexion.php';

$proveedores = mysqli_query($conn, "SELECT * FROM proveedores ORDER BY nombre_empresa ASC");
$productos = mysqli_query($conn, "SELECT * FROM productos ORDER BY nombre ASC");

include '../core/layout.php';
renderPageStart('Nueva Compra', 'compras');
?>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="panel-dark">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="mb-0 text-info">Instrumental Disponible</h2>
        
        <div class="input-group" style="max-width: 420px;">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle" id="productosTable">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Stock Actual</th>
              <th>Precio Compra</th>
              <th class="text-end">Cantidad a Ingresar</th>
            </tr>
          </thead>
          <tbody>
            <?php while($producto = mysqli_fetch_assoc($productos)) { ?>
              <tr class="product-row">
                <td>
                  <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                  <div class="text-secondary small">ID: #<?= (int)$producto['id_producto'] ?></div>
                </td>
                <td><span class="badge bg-info"><?= (int)$producto['stock'] ?> uds.</span></td>
                <td class="fw-semibold">Bs. <?= number_format((float)$producto['precio_compra'], 2) ?></td>
                <td class="text-end">
                  <input type="number" class="form-control form-control-lg text-end cantidad-input" 
                         name="cantidad[]" min="0" value="0">
                  <input type="hidden" name="id_producto[]" value="<?= (int)$producto['id_producto'] ?>">
                  <input type="hidden" name="precio[]" value="<?= (float)$producto['precio_compra'] ?>">
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="panel-dark sticky-panel">
      <h4 class="text-info mb-4"><i class="bi bi-receipt-cutoff"></i> Detalle de Compra</h4>

      <form action="guardar_compra.php" method="POST" id="compraForm" onsubmit="return validateForm()">
        <div class="mb-3">
          <label class="form-label fw-semibold">Fecha de Compra</label>
          <input type="date" name="fecha_compra" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Proveedor</label>
          <select name="id_proveedor" class="form-select" required>
            <option value="">Seleccione proveedor...</option>
            <?php 
            mysqli_data_seek($proveedores, 0);
            while($proveedor = mysqli_fetch_assoc($proveedores)): ?>
              <option value="<?= (int)$proveedor['id_proveedor'] ?>"><?= htmlspecialchars($proveedor['nombre_empresa']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Total de la Compra</label>
          <div class="input-group input-group-lg">
            <span class="input-group-text bg-dark text-white">Bs.</span>
            <input type="text" id="total_compra" name="total_compra" class="form-control text-end fw-bold fs-3" value="0.00" readonly>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-bb-primary btn-lg">
            <i class="bi bi-save"></i> Guardar Compra e Ingresar Stock
          </button>
          <button type="button" onclick="limpiarCantidades()" class="btn btn-bb-outline">
            <i class="bi bi-trash"></i> Limpiar Cantidades
          </button>
          <a href="../dashboard.php" class="btn btn-bb-outline">Cancelar</a>
        </div>

        <div class="alert alert-info mt-4 small">
          <i class="bi bi-info-circle"></i> Solo se registrarán productos con cantidad > 0.
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// JS (buscador + total)
document.getElementById('searchInput').addEventListener('keyup', function() {
  const term = this.value.toLowerCase();
  document.querySelectorAll('#productosTable tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
  });
});

function recalcTotal() {
  let total = 0;
  const cantidades = document.querySelectorAll('input[name="cantidad[]"]');
  const precios = document.querySelectorAll('input[name="precio[]"]');
  cantidades.forEach((inp, i) => {
    total += (parseFloat(inp.value)||0) * (parseFloat(precios[i].value)||0);
  });
  document.getElementById('total_compra').value = total.toFixed(2);
}

function limpiarCantidades() {
  if(confirm('¿Borrar todas las cantidades?')) {
    document.querySelectorAll('input[name="cantidad[]"]').forEach(i => i.value = 0);
    recalcTotal();
  }
}

function validateForm() {
  if(parseFloat(document.getElementById('total_compra').value) <= 0) {
    alert('Debe ingresar al menos un producto.');
    return false;
  }
  return confirm('¿Confirmar compra?');
}

document.addEventListener('DOMContentLoaded', () => {
  recalcTotal();
  document.querySelectorAll('.cantidad-input').forEach(el => el.addEventListener('input', recalcTotal));
});
</script>

<?php renderPageEnd(); ?>