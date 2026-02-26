<?php
require 'conexion.php';
// Verificar si el administrador esta autenticado
if (isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 15px 0;
        }
        a {
            display: block;
            padding: 10px;
            background: #4ce863;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        a:hover {
            background: #25b864;
        }
        .logout {
            background: #715759;
        }
        .logout:hover {
            background: #827677;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bienvenido Administrador</h1>
    <ul>
        <li><a href="activarDesactCursos.php">Activar / Desactivar Cursos</a></li>
        <li><a href="eligeCursoBarem.php">Baremación Automatica y listado de admitidos</a></li>
        <li><a href="quitarPonerCursos.php">Añadir / Eliminar Cursos</a></li>
        <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
    </ul>
</div>

</body>
</html>

