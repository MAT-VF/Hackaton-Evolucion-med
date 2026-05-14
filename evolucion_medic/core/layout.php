<?php
// core/layout.php - Versión estable para todas las carpetas
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function getBasePath() {
    $url = $_SERVER['PHP_SELF'];
    
    // Detectar profundidad de carpeta
    if (strpos($url, '/compras/') !== false ||
        strpos($url, '/productos/') !== false ||
        strpos($url, '/proveedores/') !== false ||
        strpos($url, '/inventario/') !== false) {
        return '../';
    }
    return '';
}

function renderSidebar($active = '') {
    $base = getBasePath();
    
    $items = [
        ['href' => $base . 'dashboard.php',          'label' => 'Dashboard',     'key' => 'dashboard'],
        ['href' => $base . 'compras/compras.php',    'label' => 'Compras',       'key' => 'compras'],
        ['href' => $base . 'productos/productos.php','label' => 'Productos',     'key' => 'productos'],
        ['href' => $base . 'proveedores/proveedores.php', 'label' => 'Proveedores', 'key' => 'proveedores'],
        ['href' => $base . 'inventario/inventario.php',   'label' => 'Inventario',  'key' => 'inventario'],
    ];

    echo '<div class="sidebar">';
    echo '  <div class="sidebar-header">';
    echo '    <div class="brand">EVOLUCIONMEDIC</div>';
    echo '    <div class="brand-sub">Sistema Inteligente</div>';
    echo '  </div>';
    echo '  <nav class="nav flex-column">';

    foreach ($items as $it) {
        $isActive = ($active === $it['key']);
        $cls = $isActive ? 'active' : '';
        echo '<a class="nav-link ' . $cls . '" href="' . htmlspecialchars($it['href']) . '">';
        echo $it['label'];
        echo '</a>';
    }

    echo '  </nav>';
    echo '  <div class="sidebar-footer">';
    echo '    <a class="nav-link" href="' . $base . 'logout.php">Cerrar Sesión</a>';
    echo '  </div>';
    echo '</div>';
}

function renderTopbar($title) {
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario';
    echo '<div class="topbar">';
    echo '  <div class="topbar-left">';
    echo '    <h1 class="topbar-title">' . htmlspecialchars($title) . '</h1>';
    echo '    <div class="topbar-sub">Bienvenido ' . htmlspecialchars($usuario) . '</div>';
    echo '  </div>';
    echo '</div>';
}

function renderPageStart($title, $activeKey) {
    $base = getBasePath();
    
    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '  <meta charset="UTF-8">';
    echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '  <title>' . htmlspecialchars($title) . ' - EVOLUCIONMEDIC</title>';
    
    // CSS con ruta correcta según la carpeta
    echo '  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '  <link rel="stylesheet" href="' . $base . 'css/styles.css">';
    echo '  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">';
    
    echo '</head>';
    echo '<body class="dashboard-body">';
    
    renderSidebar($activeKey);
    echo '<div class="main-content">';
    renderTopbar($title);
    echo '  <div class="page-container">';
}

function renderPageEnd() {
    echo '  </div>'; // page-container
    echo '</div>';   // main-content
    echo '</body></html>';
}