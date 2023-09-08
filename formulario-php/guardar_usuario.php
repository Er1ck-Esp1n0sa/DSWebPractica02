<?php
// Conectarse a la base de datos
$conexion = pg_connect("host=localhost port=5432 dbname=usuario user=postgres password=postgres");

if (!$conexion) {
    die("Error en la conexión a la base de datos.");
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];

// Insertar datos en la tabla
$query = "INSERT INTO usuarios (nombre, direccion, telefono, correo) VALUES ('$nombre', '$direccion', '$telefono', '$correo')";
$resultado = pg_query($conexion, $query);

if ($resultado) {
    echo "Usuario registrado correctamente.";
} else {
    echo "Error al registrar usuario.";
}

// Cerrar la conexión
pg_close($conexion);
?>
