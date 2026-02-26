<?php
session_start();
include 'conexion.php';
//mensaje es para guardar errores
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = trim($_POST['dni'] ?? '');
// si el dni vacio muestra el mensaje de error
    if (!empty($dni)) {
        $laConsulta = "SELECT dni FROM solicitantes WHERE dni = ?";
        $stmt = $conn->prepare($laConsulta);
        $stmt->execute([$dni]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        //comprueba si el usuario existe
        //si el dni existe entra al sistema
        //Si el dni no existe va a registrarse
        // En ambos casos guarda el dni en sesion para usarlo despues
        if ($usuario) {
            //permite identificar al usuario en 
            // otras paginas sin volver a pedir dni
            $_SESSION['dni'] = $usuario['dni'];
            //si como el usuario ya esta registrado
            //  puede acceder directamente
            header("Location: listadoCursos.php");
            exit();
        } else {
            $_SESSION['dni'] = $dni;
            header("Location: guardarSolicitante.php");
            exit();
        }
    } else {
        $mensaje = "Por favor, ingresa tu DNI.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso Usuario</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        width: 350px;
        text-align: center;
    }

    h3 {
        margin-bottom: 20px;
        color: #333;
    }

    input[type="text"], input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 1em;
    }

    input[type="submit"] {
        background-color: #2ec233;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #285f2f;
    }

    .error {
        color: red;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<div class="container">
    <h3>Acceso Usuario</h3>

    <?php if (!empty($mensaje)): ?>
        <p class="error"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>DNI:</label><br>
        <input type="text" name="dni" maxlength="9" value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>"><br>
        <input type="submit" value="Ingresar">
        <a href="index.php" class="button">Volver a la página principal</a>
    </form>
</div>

</body>
</html>