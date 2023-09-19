<?php
session_start();

$host = "172.17.0.3";
$port = "5432";
$dbname = "alumnos";
$user = "postgres";
$password = "postgres";

if (isset($_POST['registro'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);

        $sentencia = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, contraseña) VALUES (?, ?)");
        $resultado = $sentencia->execute([$nombre_usuario, $hashed_password]);

        if ($resultado === true) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error al registrar al usuario.";
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
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="row justify-content-center p-5">
            <div class="col-sm-6">
                <h1>Registro de Usuario</h1>
                <form action="registro.php" method="POST">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                    <label>Contraseña</label>
                    <input type="password" name="contraseña" class="form-control" required>
                    <input type="submit" class="btn btn-success" name="registro" value="Registrarse">
                </form>
                <a href="index.php" class="btn btn-secondary">Volver a Iniciar Sesión</a>
            </div>
        </div>
    </div>
</body>

</html>
