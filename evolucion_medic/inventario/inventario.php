<?php
require_once '../core/guard.php';
requireLogin();
include '../conexion.php';

include '../core/layout.php';
renderPageStart('Inventario - Stock Mínimo', 'inventario');
?>

<div class="page-container">
  <div class="row g-4">
    <div class="col-12">
      <div class="panel-dark">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
          <h2 class="text-info mb-0">Inventario (Alerta Stock Mínimo)</h2>
          
          <div class="input-group" style="max-width: 380px;">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-dark table-hover align-middle" id="inventarioTable">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Proveedor</th>
                <th class="text-center">Stock Actual</th>
                <th class="text-center">Stock Mínimo</th>
                <th class="text-center">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "
                SELECT 
                  p.*,
                  pr.nombre_empresa 
                FROM productos p
                LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
                ORDER BY p.stock ASC";

              $result = mysqli_query($conn, $query);

              while($row = mysqli_fetch_assoc($result)) {
                $stock = (int)$row['stock'];
                $minimo = (int)($row['stock_minimo'] ?? 5);
                
                if ($stock <= 0) {
                  $estadoClass = 'bg-danger';
                  $estadoTexto = 'AGOTADO';
                } elseif ($stock <= $minimo) {
                  $estadoClass = 'bg-warning text-dark';
                  $estadoTexto = 'BAJO STOCK';
                } else {
                  $estadoClass = 'bg-success';
                  $estadoTexto = 'NORMAL';
                }
              ?>
                <tr>
                  <td>
                    <strong><?= htmlspecialchars($row['nombre']) ?></strong><br>
                    <small class="text-secondary">Lote: <?= htmlspecialchars($row['lote'] ?? 'N/A') ?></small>
                  </td>
                  <td><?= htmlspecialchars($row['nombre_empresa'] ?? 'Sin proveedor') ?></td>
                  <td class="text-center fw-bold fs-5"><?= $stock ?></td>
                  <td class="text-center"><?= $minimo ?></td>
                  <td class="text-center">
                    <span class="badge <?= $estadoClass ?>"><?= $estadoTexto ?></span>
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

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
  const term = this.value.toLowerCase();
  document.querySelectorAll('#inventarioTable tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
  });
});
</script>

<?php renderPageEnd(); ?>
