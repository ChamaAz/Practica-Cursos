<?php
session_start();
require 'conexion.php'; // Conexión PDO

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

$feedback = '';
$curso_seleccionado = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    $curso_seleccionado = $_POST['curso'] ?? '';
    if (!empty($curso_seleccionado) && in_array($accion, ['activar', 'desactivar'])) {
        $abierto = $accion === 'activar' ? 1 : 0;

        $sql = "UPDATE cursos SET abierto = :abierto WHERE codigo = :codigo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':abierto', $abierto, PDO::PARAM_INT);
        $stmt->bindParam(':codigo', $curso_seleccionado, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $feedback = "<p class='feedback' style='color: green;'>Curso " . ($abierto ? "activado" : "desactivado") . " exitosamente.</p>";
        } else {
            $feedback = "<p class='feedback' style='color: red;'>Error al actualizar el curso.</p>";
        }
    } else {
        $feedback = "<p class='feedback' style='color: red;'>Seleccione un curso y acción válidos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Activar/Desactivar Cursos</title>
<style>
body { font-family: Arial; background:#f4f4f4; padding:50px; text-align:center;}
.container { background:white; padding:20px; border-radius:8px; max-width:600px; margin:auto; box-shadow:0 0 10px rgba(0,0,0,0.1);}
select, input[type="submit"] { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px;}
input[type="submit"] { background:#007bff; color:white; border:none; cursor:pointer;}
input[type="submit"]:hover { background:#0056b3;}
.feedback { font-weight:bold; margin-top:15px;}
</style>
</head>
<body>

<div class="container">
<h3>Activar o Desactivar Cursos</h3>

<?php echo $feedback; ?>

<form method="POST" action="">
    <label for="curso">Seleccione un curso:</label>
    <select name="curso" required>
        <?php
        $sql = "SELECT codigo, nombre, abierto FROM cursos";
        $stmt = $conn->query($sql);
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cursos as $row) {
            $estado = $row['abierto'] ? " (Activo)" : " (Inactivo)";
            $selected = ($curso_seleccionado === $row['codigo']) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($row['codigo']) . "' $selected>" . htmlspecialchars($row['nombre'] . $estado) . "</option>";
        }
        ?>
    </select>

    <input type="submit" name="accion" value="activar">
    <input type="submit" name="accion" value="desactivar">
    <a href="index.php" class="button">Volver a la página principal</a>
</form>
</div>

</body>
</html>