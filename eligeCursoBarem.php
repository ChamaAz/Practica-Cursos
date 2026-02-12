<?php
session_start();
require 'conexion.php'; // Conexión PDO

$curso_seleccionado = ''; // Para repintado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso_seleccionado = $_POST['curso'] ?? '';
    if (!empty($curso_seleccionado)) {
        // Redirigir a la página de baremación con el curso seleccionado
        header("Location: baremacion.php?curso=" . urlencode($curso_seleccionado));
        exit();
    }
}

// Obtener cursos cerrados y con plazo vencido
$sqlCursos = "SELECT codigo, nombre FROM cursos WHERE abierto = 0 AND plazoinscripcion < CURDATE()";
$stmt = $conn->query($sqlCursos);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Cursos para Baremación</title>
<style>
body { font-family: Arial; background:#f4f4f4; text-align:center; padding:50px; }
.container { background:white; padding:20px; border-radius:8px; max-width:500px; margin:auto; text-align:left; }
h3 { text-align:center; }
select, input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
input[type="submit"] { background:#007bff; color:white; border:none; cursor:pointer; }
input[type="submit"]:hover { background:#0056b3; }
.error { color:red; font-weight:bold; text-align:center; }
</style>
</head>
<body>

<div class="container">
    <h3>Selecciona un curso para la baremación</h3>
    <form method="POST">
        <label for="curso">Curso:</label>
        <select name="curso" id="curso" required>
            <option value="">-- Seleccione un curso --</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo htmlspecialchars($curso['codigo']); ?>" 
                    <?php echo ($curso_seleccionado == $curso['codigo']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($curso['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Enviar">
        <a href="index.php" class="button">Volver a la página principal</a>
    </form>
</div>

</body>
</html>