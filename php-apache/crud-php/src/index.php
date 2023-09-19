<?php
session_start();

if (isset($_POST['login'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    try {
        $host = "172.17.0.3";
        $port = "5432";
        $dbname = "alumnos";
        $user = "postgres";
        $password = "postgres";

        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sentencia = $pdo->prepare("SELECT id, contraseña FROM usuarios WHERE nombre_usuario = ?");
        $sentencia->execute([$nombre_usuario]);
        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            // Autenticación exitosa, establecer una sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['sesion_iniciada'] = true;
            header("Location: formulario.php");
            exit();
        } else {
            // Autenticación fallida, muestra un mensaje de error
            echo "Nombre de usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error en la conexión a la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
        crossorigin="anonymous" />
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
                    <a href="registro.php" class="btn btn-success">Registrarse</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
