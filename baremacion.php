<?php
require 'conexion.php';

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

// Verificar si se recibió un curso por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['curso'])) {
    // Obtener el código del curso
    $codigo_curso = $_POST['curso'];

    // Obtener información del curso
    $sqlCurso = "SELECT nombre, numeroplazas FROM cursos WHERE codigo = ?";
    $stmtCurso = $conn->prepare($sqlCurso);
    $stmtCurso->bind_param("i", $codigo_curso);
    $stmtCurso->execute();
    $resultCurso = $stmtCurso->get_result();
    $curso = $resultCurso->fetch_assoc();

    if (!$curso) {
        echo "<p>Error: Curso no encontrado.</p>";
        exit();
    }

    // Obtener los solicitantes inscritos en el curso
    $sqlSolicitantes = "SELECT s.dni, su.puntos, su.nombre, su.apellidos, su.coordinadortic,
                        su.grupotic, su.pbilin, su.cargo, su.nombrecargo, su.antiguedad, su.situacion
                        FROM solicitudes AS s 
                        JOIN solicitantes AS su ON s.dni = su.dni 
                        WHERE s.codigocurso = ?";

    $stmtSolicitantes = $conn->prepare($sqlSolicitantes);
    $stmtSolicitantes->bind_param("i", $codigo_curso);
    $stmtSolicitantes->execute();
    $resultSolicitantes = $stmtSolicitantes->get_result();

    if ($resultSolicitantes->num_rows === 0) {
        echo "<p>No hay solicitantes inscritos en este curso.</p>";  
        exit();
    }

    // Crear un array para almacenar los solicitantes con los puntos ajustados
    $solicitantes = [];

    while ($row = $resultSolicitantes->fetch_assoc()) {
        $puntos = intval($row['puntos']);

        // Ajustar los puntos según condiciones
        if ($row['coordinadortic']) $puntos += 4;
        if ($row['grupotic']) $puntos += 3;
        if ($row['pbilin']) $puntos += 3;

        if ($row['cargo']) {
            $cargo = strtolower($row['nombrecargo']);
            if (in_array($cargo, ['director', 'jefe de estudios', 'secretario'])) {
                $puntos += 2;
            } elseif ($cargo === 'jefe de departamento') {
                $puntos += 1;
            }
        }

        $puntos += $row['antiguedad'];  // Sumar antigüedad

        if (strtolower($row['situacion']) === 'activo') {
            $puntos += 1;
        }

        $solicitantes[] = [
            'dni' => $row['dni'],
            'nombre' => $row['nombre'],
            'apellidos' => $row['apellidos'],
            'puntos' => $puntos
        ];
    }

    // Ordenar los solicitantes por puntos de mayor a menor
    usort($solicitantes, fn($a, $b) => $b['puntos'] <=> $a['puntos']);

    // Mostrar la tabla de solicitantes
    echo "<h2>Baremación del Curso: " . htmlspecialchars($curso['nombre']) . " (" . $codigo_curso . ")</h2>";
    echo "<table border='1'>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Puntos</th>
                <th>Estado</th>
            </tr>";

    foreach ($solicitantes as $index => $solicitante) {
        $estado = ($index < $curso['numeroplazas']) ? "Admitido" : "En lista de espera";
        $admitido = ($estado === "Admitido") ? 1 : 0;

        // Actualizar estado en la base de datos
        $sqlActualizar = "UPDATE solicitudes SET admitido = ? WHERE dni = ? AND codigocurso = ?";
        $stmtActualizar = $conn->prepare($sqlActualizar);
        $stmtActualizar->bind_param("isi", $admitido, $solicitante['dni'], $codigo_curso);
        $stmtActualizar->execute();

        echo "<tr>
                <td>" . htmlspecialchars($solicitante['dni']) . "</td>
                <td>" . htmlspecialchars($solicitante['nombre']) . "</td>
                <td>" . htmlspecialchars($solicitante['apellidos']) . "</td>
                <td>" . $solicitante['puntos'] . "</td>
                <td>" . $estado . "</td>
              </tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baremación de Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h3, h4 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .feedback {
            font-weight: bold;
            margin-top: 15px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h3>Baremación del Curso: <?php echo htmlspecialchars($curso['nombre']) . " - " . htmlspecialchars($codigo_curso); ?></h3>
    <p>Número de plazas: <?php echo $curso['numeroplazas']; ?></p>
       <a href="index.php" class="button">Volver a la página principal</a>
</body>
</html>