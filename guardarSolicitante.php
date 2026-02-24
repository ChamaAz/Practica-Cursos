<?php
session_start();
include 'conexion.php';

$mensaje = '';
$tipoMensaje = ''; //error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $codigocentro = $_POST['codigocentro'] ?? '';
    $coordinadortic = isset($_POST['coordinadortic']) ? 1 : 0;
    $grupotic = isset($_POST['grupotic']) ? 1 : 0;
    $nombregrupo = $_POST['nombregrupo'] ?? '';
    $pbilin = isset($_POST['pbilin']) ? 1 : 0;
    $cargo = isset($_POST['cargo']) ? 1 : 0;
    $nombrecargo = $_POST['nombrecargo'] ?? '';
    $situacion = $_POST['situacion'] ?? 'activo';
    $antiguedad = isset($_POST['antiguedad']) ? intval($_POST['antiguedad']) : 0;
    $especialidad = $_POST['especialidad'] ?? '';
    $puntos = isset($_POST['puntos']) ? intval($_POST['puntos']) : 0;

    if (!empty($dni) && !empty($apellidos) && !empty($nombre) && !empty($telefono) && !empty($correo)) {
        $sql = "INSERT INTO solicitantes
        (dni, apellidos, nombre, telefono, correo, codigocentro, coordinadortic, grupotic, nombregrupo, pbilin, cargo, nombrecargo, situacion, antiguedad, especialidad, puntos) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $dni, $apellidos, $nombre, $telefono, $correo, $codigocentro,
            $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo,
            $nombrecargo, $situacion, $antiguedad, $especialidad, $puntos
        ]);

        $_SESSION['dni'] = $dni;
        $mensaje = "Solicitante agregado exitosamente. DNI: $dni";
        $tipoMensaje = 'success';

        // Limpiar POST
        $_POST = [];
    } else {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
        $tipoMensaje = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrarse</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 50px 0;
}

.container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

h3 {
    text-align: center;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

input[type="text"], input[type="email"], input[type="number"], input[type="date"], select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

input[type="checkbox"] {
    margin-right: 5px;
}

input[type="submit"], a.button {
    display: inline-block;
    width: 100%;
    text-align: center;
    background-color: #007bff;
    color: white;
    padding: 10px;
    margin-top: 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 1em;
}

input[type="submit"]:hover, a.button:hover {
    background-color: #0056b3;
}

p.success {
    color: green;
    font-weight: bold;
    text-align: center;
}

p.error {
    color: red;
    font-weight: bold;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
<h3>Registrar Solicitante</h3>

<?php if (!empty($mensaje)): ?>
    <p class="<?php echo $tipoMensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>DNI:</label>
    <input type="text" name="dni" maxlength="9" value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>">

    <label>Apellidos:</label>
    <input type="text" name="apellidos" maxlength="50" value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" maxlength="50" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

    <label>Teléfono:</label>
    <input type="text" name="telefono" maxlength="12" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">

    <label>Correo:</label>
    <input type="email" name="correo" maxlength="50" value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">

    <label>Código Centro:</label>
    <input type="text" name="codigocentro" maxlength="8" value="<?php echo htmlspecialchars($_POST['codigocentro'] ?? ''); ?>">

    <label><input type="checkbox" name="coordinadortic" value="1" <?php if(isset($_POST['coordinadortic'])) echo "checked"; ?>> Coordinador TIC</label>

    <label><input type="checkbox" name="grupotic" value="1" <?php if(isset($_POST['grupotic'])) echo "checked"; ?>> Grupo TIC</label>

    <label>Nombre Grupo:</label>
    <input type="text" name="nombregrupo" maxlength="25" value="<?php echo htmlspecialchars($_POST['nombregrupo'] ?? ''); ?>">

    <label><input type="checkbox" name="pbilin" value="1" <?php if(isset($_POST['pbilin'])) echo "checked"; ?>> Programa Bilingüe</label>

    <label><input type="checkbox" name="cargo" value="1" <?php if(isset($_POST['cargo'])) echo "checked"; ?>> Cargo</label>

    <label>Nombre Cargo:</label>
    <input type="text" name="nombrecargo" maxlength="50" value="<?php echo htmlspecialchars($_POST['nombrecargo'] ?? ''); ?>">

    <label>Situación:</label>
    <select name="situacion">
        <option value="activo" <?php if(($_POST['situacion'] ?? '')=='activo') echo "selected"; ?>>Activo</option>
        <option value="inactivo" <?php if(($_POST['situacion'] ?? '')=='inactivo') echo "selected"; ?>>Inactivo</option>
    </select>
    <label>Antigüedad (años):</label>
    <input type="number" name="antiguedad" min="0" max="99" value="<?php echo htmlspecialchars($_POST['antiguedad'] ?? ''); ?>">
    <label>Especialidad:</label>
    <input type="text" name="especialidad" maxlength="50" value="<?php echo htmlspecialchars($_POST['especialidad'] ?? ''); ?>">
    <label>Puntos:</label>
    <input type="number" name="puntos" min="0" max="999" value="<?php echo htmlspecialchars($_POST['puntos'] ?? ''); ?>">
    <input type="submit" value="Agregar Solicitante">
    <a href="index.php" class="button">Volver a la página principal</a>
</form>
</div>
</body>
</html>