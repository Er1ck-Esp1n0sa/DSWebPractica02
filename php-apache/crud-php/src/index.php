<?php
$host = "172.17.0.3";
$port = "5432";
$dbname = "alumnos";
$user = "postgres"; 
$password = "postgres"; 

$edit_mode = false; // Variable para controlar el modo de edición

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

// Verifica si se ha enviado el formulario de alta de alumnos
if (isset($_POST["insert"])) {
    // Obtener los valores del formulario
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];

    try {
        // Consulta SQL para insertar un nuevo registro en la tabla "alumnos"
        $sentencia = $pdo->prepare("INSERT INTO alumnos (nombre, direccion, telefono, correo) VALUES (?, ?, ?, ?)");
        $resultado = $sentencia->execute([$nombre, $direccion, $telefono, $correo]);

        if ($resultado === true) {
            // Redirigir a la página principal después de insertar
            header("Location: index.php");
            exit(); // Importante: terminar el script después de redirigir
        } else {
            echo "Error al insertar el alumno en la base de datos.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta a la base de datos: " . $e->getMessage();
    }
}

// Código para obtener la lista de alumnos
try {
    // Consulta SQL para seleccionar datos de la tabla "alumnos"
    $sentencia = $pdo->query("SELECT id, nombre, direccion, correo, telefono FROM alumnos");

    // Obtener los datos en forma de objetos
    $alumnos = $sentencia->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "Error en la consulta a la base de datos: " . $e->getMessage();
}

// Código para eliminar un alumno
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    try {

        $sentencia = $pdo->prepare("DELETE FROM alumnos WHERE id = ?;");
        $resultado = $sentencia->execute([$id]);

        if ($resultado === true) {
            header("Location: index.php");
            exit();
        } else {
            echo "Algo salió mal";
        }
    } catch (PDOException $e) {
        echo "Error en la conexión o consulta a la base de datos: " . $e->getMessage();
    }
}

// Código para cargar los datos del alumno seleccionado para edición
if (isset($_GET["edit_id"])) {
    $edit_id = $_GET["edit_id"];
    $edit_mode = true; // Establece el modo de edición
    
    try {

        $sentencia = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?;");
        $sentencia->execute([$edit_id]);
        $alumno_edit = $sentencia->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo "Error en la conexión o consulta a la base de datos: " . $e->getMessage();
    }
}

// Código para actualizar los datos del alumno
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];

    try {

        $sentencia = $pdo->prepare("UPDATE alumnos SET nombre = ?, direccion = ?, correo = ?, telefono = ? WHERE id = ?;");
        $resultado = $sentencia->execute([$nombre, $direccion, $correo, $telefono, $id]);

        if ($resultado === true) {
            header("Location: index.php");
            exit();
        } else {
            echo "Algo salió mal al actualizar el alumno";
        }
    } catch (PDOException $e) {
        echo "Error en la conexión o consulta a la base de datos: " . $e->getMessage();
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
                <h1>Alta Alumnos Formulario</h1>
                <form action="index.php" method="POST">
                    <?php if ($edit_mode && isset($alumno_edit)): ?>

                        <div class="container">
                            <div class="row justify-content-center p-5">
                                <div class="col-sm-6">
                                    <h1>Iniciar Sesión</h1>
                                    <form action="login.php" method="POST">
                                        <label>Nombre de Usuario</label>
                                        <input type="text" name="nombre_usuario" class="form-control" required>
                                        <label>Contraseña</label>
                                        <input type="password" name="contraseña" class="form-control" required>
                                        <input type="submit" class="btn btn-primary" name="login" value="Iniciar Sesión">
                                    </form>
                                </div>
                            </div>
                        </div>

                    <!-- En modo de edición, muestra el ID oculto para identificar al alumno -->
                    <input type="hidden" name="id" value="<?php echo $alumno_edit->id; ?>">
                    <?php endif; ?>
                    <label>Nombre del alumno</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ingrese el nombre del alumno"
                        value="<?php if ($edit_mode && isset($alumno_edit)) echo $alumno_edit->nombre; ?>" required>
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" placeholder="Ingrese dirección del alumno"
                        value="<?php if ($edit_mode && isset($alumno_edit)) echo $alumno_edit->direccion; ?>" required>
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" step="any"
                        placeholder="Ingrese número de teléfono"
                        value="<?php if ($edit_mode && isset($alumno_edit)) echo $alumno_edit->telefono; ?>" required>
                    <label>Correo electrónico</label>
                    <input type="text" name="correo" class="form-control" placeholder="Ingrese correo electrónico"
                        value="<?php if ($edit_mode && isset($alumno_edit)) echo $alumno_edit->correo; ?>" required><br>
                    <?php if ($edit_mode): ?>
                    <!-- En modo de edición, cambia el nombre del botón a "Guardar cambios" -->
                    <input type="submit" class="btn btn-primary" name="update" value="Guardar cambios" />
                    <input type="button" class="btn btn-danger" value="Volver" onclick="window.history.back();" />
                    <?php else: ?>
                    <!-- En modo normal, muestra el botón de "Guardar" -->
                    <input type="submit" class="btn btn-primary" name="insert" value="Guardar" />
                    <input type="reset" class="btn btn-danger" value="Restablecer campos" />
                    <?php endif; ?>
                </form>
                <br>
                <hr />
                <!-- Lista de Alumnos -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td>
                                <a href="<?php echo " index.php?edit_id=" . $alumno->id ?>">
                                    <?php echo $alumno->id; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $alumno->nombre; ?>
                            </td>
                            <td>
                                <?php echo $alumno->direccion; ?>
                            </td>
                            <td>
                                <?php echo $alumno->correo; ?>
                            </td>
                            <td>
                                <?php echo $alumno->telefono; ?>
                            </td>
                            <td>
                                <!-- Agregar el script de confirmación antes de redirigir a la página de eliminación -->
                                <a class="btn btn-danger" href="<?php echo " javascript:confirmDelete(" . $alumno->id .
                                    ")" ?>">Eliminar</a>
                            </td>
                            <td>
                                <a class="btn btn-warning" href="<?php echo "index.php?edit_id=" . $alumno->id; ?>">Modificar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    function confirmDelete(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este alumno?")) {
            // Si el usuario confirma la eliminación, redirige a la página de eliminación
            window.location.href = "index.php?id=" + id;
        }
    }
</script>

</html>

