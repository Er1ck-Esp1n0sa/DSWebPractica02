<?php
session_start();

if (isset($_POST['login'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    // Verificar si el nombre de usuario y la contraseña son "admin"
    if ($nombre_usuario === "admin" && $contraseña === "admin") {
        // Autenticación exitosa, establecer una sesión
        $_SESSION['usuario_id'] = 1; // Puedes establecer cualquier valor que desees aquí
        header("Location: formulario.php");
        exit();
    } else {
        // Autenticación fallida, muestra un mensaje de error
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="row justify-content-center p-5">
            <div class="col-sm-6">
                <h1>Iniciar Sesión</h1>
                <form action="index.php" method="POST">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                    <label>Contraseña</label>
                    <input type="password" name="contraseña" class="form-control" required>
                    <input type="submit" class="btn btn-primary" name="login" value="Iniciar Sesión">
                </form>
            </div>
        </div>
    </div>
</body>

</html>
