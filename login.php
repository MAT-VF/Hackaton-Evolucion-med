<?php
session_start();
include 'conexion.php';

if(isset($_POST['login'])) {

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM usuarios
    WHERE usuario='$usuario' AND contraseña='$password'");

    if(mysqli_num_rows($query) > 0) {

        $datos = mysqli_fetch_assoc($query);

        $_SESSION['usuario'] = $datos['nombre'];
        $_SESSION['rol'] = $datos['rol'];
        $_SESSION['id_usuario'] = (int)($datos['id_usuario'] ?? 0);

        header('Location: dashboard.php');

    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-body">

<div class="login-container">

    <div class="login-card">

        <h1 class="logo-title">EVOLUCIONMEDIC</h1>
        <p class="subtitle">Sistema Inteligente Médico</p>

        <?php if(isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input type="text" name="usuario" class="form-control mb-3" placeholder="Usuario">

            <input type="password" name="password" class="form-control mb-4" placeholder="Contraseña">

            <button type="submit" name="login" class="btn btn-login w-100">
                Ingresar
            </button>

        </form>

    </div>

</div>

</body>
</html>