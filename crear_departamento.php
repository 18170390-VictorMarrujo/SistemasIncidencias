<?php
session_start();
if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_jefe = $_POST['id_jefe'];

    $query = "INSERT INTO departamentos (nombre, id_jefe) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $nombre, $id_jefe);
    if ($stmt->execute()) {
        header('Location: listar_departamentos.php');
        exit;
    } else {
        $error = "Error al crear el departamento.";
    }
}

$jefes_query = "SELECT id_usuario, nombre FROM usuarios WHERE tipo_usuario = 'jefe_departamento'";
$jefes_result = $conn->query($jefes_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Departamento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Crear Departamento</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="crear_departamento.php">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Departamento</label>
            <input type="text" name="nombre" class="form-control" id="nombre" required>
        </div>
        <div class="mb-3">
            <label for="id_jefe" class="form-label">Jefe de Departamento</label>
            <select name="id_jefe" id="id_jefe" class="form-select">
                <option value="">Sin asignar</option>
                <?php while ($jefe = $jefes_result->fetch_assoc()): ?>
                    <option value="<?php echo $jefe['id_usuario']; ?>"><?php echo $jefe['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Departamento</button>
    </form>
</div>
</body>
</html>
