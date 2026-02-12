<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #333;
        }

        a {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #4dc764;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #185f15;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        li {
            background: #e3f2fd;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Bienvenidos!</h2>

        <a href="accesoAdmin.php">Login Administradores</a><br>
        <a href="accesoUsuario.php">Login Usuario/Profesor</a><br>
        <a href="guardarSolicitante.php">Regístrate</a>

        <h1>Cursos Disponibles</h1>

        <?php
        include 'conexion.php';
        // Mostrar los cursos solo si están activos
        $laConsulta = "SELECT codigo, nombre FROM cursos WHERE abierto = 1"; 
        $stmt = $conn->prepare($laConsulta);
        $stmt->execute();
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($cursos)) {
            echo "<ul>";
            foreach ($cursos as $curso) {
                echo "<li>" . htmlspecialchars($curso['nombre']) . " (Código: " . htmlspecialchars($curso['codigo']) . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No hay cursos disponibles.</p>";
        }

        $conn = null; // cerrar la conexión
        ?>
    </div>
</body>
</html>