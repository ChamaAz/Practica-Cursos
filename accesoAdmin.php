<?php
session_start();
include 'conexion.php';
$mensaje = '';
$datos= '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = trim($_POST['dni']);
    $datos = $dni;
    if (!empty($dni)) {
        //preparamos la consulta
        $sql_dniAdmin = "SELECT dni FROM administradores WHERE dni = ?";
        $stmt = $conn->prepare($sql_dniAdmin);
        if ($stmt) {
            // Ejecutar consulta con parametro
            $stmt->execute([$dni]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $_SESSION['dni'] = $dni; // guardarmos en sesion
                header("Location: adminMenu.php");
                exit();
            } else {
                $mensaje = "El DNI no coincide con ningun administrador.";
            }
        } else {
            $mensaje = "Error en la consulta a la base de datos.";
        }
    } else {
        $mensaje = "Por favor, ingresa tu DNI.";
    }
}

// Cerrar la conx 
$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso Administrador</title>
<style>
body { font-family: Arial; background:#f4f4f4; text-align:center; padding:50px; }
.container { background:white; padding:20px; border-radius:8px; max-width:400px; margin:auto; box-shadow:0 0 10px rgba(0,0,0,0.1);}
input[type="text"], input[type="submit"] { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px;}
input[type="submit"] { background:#28a745; color:white; border:none; cursor:pointer;}
input[type="submit"]:hover { background:#218838;}
.error { color:red; font-weight:bold; }
</style>
</head>
<body>

<div class="container">
<h3>Acceso Administrador</h3>
<form method="POST" action="">
    <label for="dni">DNI:</label>
    <input type="text" name="dni" value="<?php echo htmlspecialchars($datos); ?>">
    <input type="submit" value="Ingresar">
    <a href="index.php" class="button">Volver a la página principal</a>
</form>
<?php if (!empty($mensaje)): ?>
    <p class="error"><?php echo htmlspecialchars($mensaje); ?></p>
<?php endif; ?>
</div>

</body>
</html>