<?php
session_start();
include 'conexion.php';

include 'core/guard.php';
requireLogin();

$totalProductos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM productos"));
$totalCompras = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM compras"));
$totalProveedores = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM proveedores"));

include 'core/layout.php';
renderPageStart('Panel Administrativo', 'dashboard');
?>

<div class="page-container">
  <div class="row g-4 mt-2">
    <div class="col-md-4">
      <div class="card-dashboard">
        <h3>Productos</h3>
        <h1><?php echo (int)$totalProductos['total']; ?></h1>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-dashboard">
        <h3>Compras</h3>
        <h1><?php echo (int)$totalCompras['total']; ?></h1>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-dashboard">
        <h3>Proveedores</h3>
        <h1><?php echo (int)$totalProveedores['total']; ?></h1>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-1">
    <div class="col-lg-8">
      <div class="panel-dark">
        <h4 class="text-info bb-title">Resumen</h4>
        <div class="text-muted bb-muted">
          Administra productos, registra compras y mantén el inventario bajo control.
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="panel-dark sticky-panel">
        <h4 class="text-info bb-title">Acciones rápidas</h4>
        <div class="d-flex flex-column gap-2">
          <a href="compras/compras.php" class="btn btn-bb-primary">Registrar compra</a>
          <a href="inventario/inventario.php" class="btn btn-bb-outline">Ver stock mínimo</a>
        </div>
      </div>
    </div>
  </div>
</div>


<?php renderPageEnd(); ?>


