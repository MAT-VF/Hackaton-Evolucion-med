<?php
require_once '../core/guard.php';
requireLogin();
include '../conexion.php';

$mensaje='';
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion']??'')==='crear') {
  $nombre_empresa = trim($_POST['nombre_empresa']??'');
  $telefono = trim($_POST['telefono']??'');
  $direccion = trim($_POST['direccion']??'');
  $correo = trim($_POST['correo']??'');

  if ($nombre_empresa !== '') {
    $stmt = mysqli_prepare($conn,"INSERT INTO proveedores (nombre_empresa, telefono, direccion, correo) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt,'ssss',$nombre_empresa,$telefono,$direccion,$correo);
    mysqli_stmt_execute($stmt);
    $mensaje='Proveedor creado correctamente.';
  }
}

$proveedores = mysqli_query($conn,"SELECT * FROM proveedores ORDER BY id_proveedor DESC");

include '../core/layout.php';
renderPageStart('Proveedores','proveedores');
?>

<div class="page-container">
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="panel-dark sticky-panel">
        <h4 class="text-info" style="font-weight:900;margin-bottom:16px;">Nuevo proveedor</h4>
        <?php if($mensaje): ?>
          <div class="alert alert-success border-0" style="background:rgba(23,212,232,.12);color:var(--text);">$mensaje</div>
        <?php endif; ?>
        <form method="POST">
          <input type="hidden" name="accion" value="crear">

          <label class="form-label">Nombre empresa</label>
          <input class="form-control mb-2" name="nombre_empresa" required>

          <label class="form-label">Teléfono</label>
          <input class="form-control mb-2" name="telefono">

          <label class="form-label">Dirección</label>
          <textarea class="form-control mb-2" name="direccion" rows="3"></textarea>

          <label class="form-label">Correo</label>
          <input class="form-control mb-3" name="correo" type="email">

          <button class="btn" type="submit" style="background:linear-gradient(90deg,var(--accent),var(--accent2));color:#fff;font-weight:900;border:none;border-radius:14px;">Crear</button>
        </form>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="panel-dark">
        <h4 class="text-info" style="font-weight:900;margin-bottom:16px;">Listado</h4>
        <div class="table-responsive">
          <table class="table table-dark table-hover align-middle">
            <thead>
              <tr>
                <th>Proveedor</th>
                <th>Contacto</th>
              </tr>
            </thead>
            <tbody>
              <?php while($prov = mysqli_fetch_assoc($proveedores)) { ?>
                <tr>
                  <td>
                    <div style="font-weight:900;"><?php echo htmlspecialchars($prov['nombre_empresa']); ?></div>
                    <div style="font-size:12px;color:var(--muted);"><?php echo htmlspecialchars($prov['direccion'] ?? ''); ?></div>
                  </td>
                  <td>
                    <div><b>Tel:</b> <?php echo htmlspecialchars($prov['telefono'] ?? ''); ?></div>
                    <div style="font-size:12px; color:var(--muted);"><b>Correo:</b> <?php echo htmlspecialchars($prov['correo'] ?? ''); ?></div>
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

