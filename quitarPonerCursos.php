<?php
session_start();
require 'conexion.php'; // Conexión PDO

// Verificar que el administrador esté autenticado
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

$mensaje = '';

// Variables para repintar
$codigo_value = '';
$nombre_value = '';
$abierto_value = 0;
$numeroplazas_value = '';
$plazoinscripcion_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        // AGREGAR CURSO
        if ($accion == 'agregar') {
            $codigo = trim($_POST['codigo']);
            $nombre = trim($_POST['nombre']);
            $abierto = isset($_POST['abierto']) ? 1 : 0;
            $numeroplazas = intval($_POST['numeroplazas']);
            $plazoinscripcion = $_POST['plazoinscripcion'];

            // Guardar para repintado
            $codigo_value = $codigo;
            $nombre_value = $nombre;
            $abierto_value = $abierto;
            $numeroplazas_value = $numeroplazas;
            $plazoinscripcion_value = $plazoinscripcion;

            if (!empty($codigo) && !empty($nombre) && !empty($numeroplazas) && !empty($plazoinscripcion)) {
                $sql = "INSERT INTO cursos (codigo, nombre, abierto, numeroplazas, plazoinscripcion) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt->execute([$codigo, $nombre, $abierto, $numeroplazas, $plazoinscripcion])) {
                    $mensaje = "<p class='feedback' style='color: green;'>Curso agregado exitosamente.</p>";
                    // Limpiar repintado
                    $codigo_value = $nombre_value = $numeroplazas_value = $plazoinscripcion_value = '';
                    $abierto_value = 0;
                } else {
                    $mensaje = "<p class='feedback' style='color: red;'>Error al agregar el curso.</p>";
                }
            } else {
                $mensaje = "<p class='feedback' style='color: red;'>Todos los campos son obligatorios.</p>";
            }
        }

        // ELIMINAR CURSO
        if ($accion == 'eliminar') {
            $codigo = $_POST['curso'];
            $sql = "DELETE FROM cursos WHERE codigo = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute([$codigo])) {
                $mensaje = "<p class='feedback' style='color: green;'>Curso eliminado exitosamente.</p>";
            } else {
                $mensaje = "<p class='feedback' style='color: red;'>Error al eliminar el curso.</p>";
            }
        }
    }
}

// Obtener lista de cursos existentes
$sql = "SELECT * FROM cursos ORDER BY nombre";
$stmtCursos = $conn->query($sql);
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrar Cursos</title>
<style>
body { font-family: Arial; background:#f4f4f4; padding:50px; }
.container { background:white; padding:20px; border-radius:8px; max-width:800px; margin:auto; }
h3, h4 { text-align:center; color:#333; }
form { text-align:left; margin-bottom:30px; }
label { font-weight:bold; display:inline-block; margin-bottom:8px; margin-top:10px; }
input[type="text"], input[type="number"], input[type="date"], select {
    width:100%; padding:10px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc;
}
input[type="checkbox"] { margin-top:15px; }
input[type="submit"] {
    background:#007bff; color:white; padding:10px 20px; border:none; border-radius:5px;
    margin:10px 0; cursor:pointer; width:100%;
}
input[type="submit"]:hover { background:#0056b3; }
.feedback { font-weight:bold; margin-top:15px; }
</style>
</head>
<body>

<div class="container">
<h3>Administrar Cursos</h3>

<?php echo $mensaje; ?>

<!-- Formulario para añadir un curso -->
<h4>Añadir Curso</h4>
<form method="POST">
    <input type="hidden" name="accion" value="agregar">
    <label for="codigo">Código:</label>
    <input type="text" name="codigo" value="<?php echo htmlspecialchars($codigo_value); ?>" required>

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre_value); ?>" required>

    <label for="abierto">Abierto:</label>
    <input type="checkbox" name="abierto" value="1" <?php echo $abierto_value ? 'checked' : ''; ?> >

    <label for="numeroplazas">Número de Plazas:</label>
    <input type="number" name="numeroplazas" value="<?php echo htmlspecialchars($numeroplazas_value); ?>" required>

    <label for="plazoinscripcion">Plazo de Inscripción:</label>
    <input type="date" name="plazoinscripcion" value="<?php echo htmlspecialchars($plazoinscripcion_value); ?>" required>

    <input type="submit" value="Añadir Curso">
</form>

<!-- Formulario para eliminar un curso -->
<h4>Eliminar Curso</h4>
<form method="POST">
    <input type="hidden" name="accion" value="eliminar">
    <label for="curso">Seleccionar Curso:</label>
    <select name="curso" required>
        <?php foreach ($cursos as $curso): ?>
            <option value="<?php echo htmlspecialchars($curso['codigo']); ?>">
                <?php echo htmlspecialchars($curso['nombre'] . " - " . $curso['codigo']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Eliminar Curso">
    <a href="index.php" class="button">Volver a la página principal</a>
</form>
</div>

</body>
</html>