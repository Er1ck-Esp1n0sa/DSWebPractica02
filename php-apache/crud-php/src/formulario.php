<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario CRUD</title>
</head>
<body>
    <h1>Formulario CRUD</h1>

    <?php
    $host = "localhost"
    $dbname = "ejemplo";
    $user = "postgres";
    $password = "postgres";

    try {
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }

    function mostrarRegistros() {
        global $db;
        $query = "SELECT * FROM tabla_registro";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Registros</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Clave</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Acciones</th></tr>";
        foreach ($registros as $registro) {
            echo "<tr>";
            echo "<td>{$registro['clave']}</td>";
            echo "<td>{$registro['nombre']}</td>";
            echo "<td>{$registro['direccion']}</td>";
            echo "<td>{$registro['telefono']}</td>";
            echo "<td><a href='formulario.php?editar={$registro['id']}'>Editar</a> | ";
            echo "<form method='post' action='formulario.php'>";
            echo "<input type='hidden' name='borrar_id' value='{$registro['id']}'>";
            echo "<input type='submit' name='confirmar_borrar' value='Borrar'>";
            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    if (isset($_POST['guardar'])) {
        $clave = $_POST['clave'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        if (isset($_GET['editar'])) {
            $id = $_GET['editar'];
            $query = "UPDATE tabla_registro SET clave=?, nombre=?, direccion=?, telefono=? WHERE id=?";
            $stmt = $db->prepare($query);
            $stmt->execute([$clave, $nombre, $direccion, $telefono, $id]);
        } else {
            $query = "INSERT INTO tabla_registro (clave, nombre, direccion, telefono) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$clave, $nombre, $direccion, $telefono]);
        }
    }

    
    if (isset($_POST['confirmar_borrar'])) {
        $id = $_POST['borrar_id'];
        $query = "DELETE FROM tabla_registro WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
    }
    ?>
    <h2>Formulario</h2>
    <form method="post" action="formulario.php">
        <input type="hidden" name="id" value="<?php echo isset($_GET['editar']) ? $_GET['editar'] : ''; ?>">
        Clave: <input type="text" name="clave" value="<?php echo isset($_GET['editar']) ? $clave : ''; ?>"><br>
        Nombre: <input type="text" name="nombre" value="<?php echo isset($_GET['editar']) ? $nombre : ''; ?>"><br>
        Dirección: <input type="text" name="direccion" value="<?php echo isset($_GET['editar']) ? $direccion : ''; ?>"><br>
        Teléfono: <input type="text" name="telefono" value="<?php echo isset($_GET['editar']) ? $telefono : ''; ?>"><br>
        <?php if (isset($_GET['editar'])) : ?>
            <input type="submit" name="actualizar" value="Actualizar">
        <?php else : ?>
            <input type="submit" name="guardar" value="Guardar">
        <?php endif; ?>
        <input type="reset" value="Limpiar">
    </form>

    <?php
    mostrarRegistros();
    ?>

</body>
</html>
