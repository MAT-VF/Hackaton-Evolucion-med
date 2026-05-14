<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function requireLogin(): void {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
}

