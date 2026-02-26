<?php
session_start();
require 'conexion.php';
//si el usuario no esta logeado sale de la aplicacion
if (!isset($_SESSION['dni'])) {
    echo "<p class='error'>Debes iniciar sesión para realizar esta acción.</p>";
    exit();
}
require 'fpdf186/fpdf.php';
require 'C:\xampp\htdocs\Cursos\src\PHPMailer.php';
require 'C:\xampp\htdocs\Cursos\src\SMTP.php';
require 'C:\xampp\htdocs\Cursos\src\Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$dni = $_SESSION['dni'];
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigocurso = $_POST['curso'] ?? '';
    if (!empty($codigocurso)) {
        $fechasolicitud = date('Y-m-d');
        $admitido = 0;
        //verificar si existe algun solicitud
        $Verifica = "SELECT dni FROM solicitudes WHERE dni = ? AND codigocurso = ?";
        $stmtVerifica = $conn->prepare($Verifica);
        $stmtVerifica->execute([$dni, $codigocurso]);
        if ($stmtVerifica->rowCount() > 0) {
            $mensaje = "<p class='error'>La solicitud para este curso ya está registrada.</p>";
        } else {
            //insertar solicitud
            $insert = "INSERT INTO solicitudes (dni, codigocurso, fechasolicitud, admitido) 
                          VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($insert);
            if ($stmtInsert->execute([$dni, $codigocurso, $fechasolicitud, $admitido])) {
                //aqui obtener nombre del curso
                $miCunsultaCur = "SELECT nombre FROM cursos WHERE codigo = ?";
                $stmtCur = $conn->prepare($miCunsultaCur);
                $stmtCur->execute([$codigocurso]);
                $curso = $stmtCur->fetch(PDO::FETCH_ASSOC);
                //aqui creamos el  PDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(200, 10, 'Resguardo de Inscripcion', 0, 1, 'C');
                $pdf->Ln(10);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(100, 10, 'DNI: ' . $dni, 0, 1);
                $pdf->Cell(100, 10, 'Curso: ' . htmlspecialchars($curso['nombre']), 0, 1);
                $pdf->Cell(100, 10, 'Fecha de Solicitud: ' . $fechasolicitud, 0, 1);
                $pdf->Cell(100, 10, 'Estado: Pendiente de Aprobacion', 0, 1);
                if (!file_exists('pdfGenerado')) {
                    mkdir('pdfGenerado', 0777, true);
                }
                //guarda PDF en el servidor
                $rutaPdf = 'pdfGenerado/pdf_' . $dni . '.pdf';
                $pdf->Output('F', $rutaPdf);
                //obtener correo del usuario
                $email = "SELECT correo FROM solicitantes WHERE dni = ?";
                $stmtEmail = $conn->prepare($email);
                $stmtEmail->execute([$dni]);
                $usuario = $stmtEmail->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    $emailUsuario = $usuario['correo'];
                    //enviar correo
                   $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = '127.0.0.1';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ana@scarlatti.com';
                    $mail->Password = 'ana';
                    $mail->SMTPSecure = false;    
                    $mail->SMTPAutoTLS= false;    
                    $mail->Port= 1587;// el unico puerto que funciona en clase
                    $mail->setFrom('ana@scarlatti.com', 'Ana');
                    $mail->addAddress($emailUsuario);
                    $mail->isHTML(true);
                    $mail->Subject= 'Resguardo de Inscripción';
                    $mail->Body= '<p>Se ha generado un nuevo resguardo de inscripción.</p>';
                    $mail->addAttachment($rutaPdf);
                      //
                     // debug para ver la conexion SMTP
                     // $mail->SMTPDebug  = 3; 
                     //$mail->Debugoutput = 'html';

                    $mail->send();
                     //no lo pide el ejercicio pero lo he hecho pa ver los datos guardados 
                     // y si se genera el pdf correctamnte 
                    $mensaje = "
                    <div class='resguardo'>
                        <h3>Resguardo de Inscripción</h3>
                        <p><strong>DNI:</strong> $dni</p>
                        <p><strong>Curso:</strong> " . htmlspecialchars($curso['nombre']) . "</p>
                        <p><strong>Fecha:</strong> $fechasolicitud</p>
                        <p><strong>Estado:</strong> Pendiente de Aprobación</p>
                        <p>Correo enviado a: " . htmlspecialchars($emailUsuario) . "</p>
                    </div>";

                } catch (Exception $e) {
                    $mensaje = "<p class='error'>Error al enviar el correo: {$mail->ErrorInfo}</p>";
                }
                } else {
                    $mensaje = "<p class='error'>No se encontró el correo del usuario.</p>";
                }
            } else {
                $mensaje = "<p class='error'>Error al registrar la solicitud.</p>";
            }
        }
    } else {
        $mensaje = "<p class='error'>Por favor, seleccione un curso.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<!--un poco de estilos-->
<title>Lista de Cursos</title>
        <style>
         body { font-family: Arial; background:#f4f4f4; text-align:center; padding:40px; }
        .container { background:white; padding:20px; border-radius:8px; width:500px; margin:auto; }
        .error { color:red; font-weight:bold; }
        .resguardo { background:#e3f2fd; padding:15px; border-radius:8px; margin-top:15px; }
         select, input { width:100%; padding:8px; margin:10px 0; }
        input[type=submit]{ background:#007bff; color:white; border:none; cursor:pointer; }
        </style>
</head>
<body>
    <div class="container">
    <h3>Selecciona un Curso</h3>
        <form method="POST">
        <?php if (!empty($mensaje)) echo $mensaje; ?>
        <select name="curso">
        <?php
        //mostrar los cursos que existen en la base de datos
        $miConsulta = "SELECT codigo, nombre FROM cursos WHERE abierto = 1";
        $stmt = $conn->query($miConsulta);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['codigo']}'>" . htmlspecialchars($row['nombre']) . "</option>";
            }
        ?>
        </select>
        <input type="submit" value="Inscribirse">
        <a href="index.php" class="button">Volver a la página principal</a>
        </form>
    </div>
</body>
</html>
