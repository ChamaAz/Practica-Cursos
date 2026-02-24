<?php
// Después de verificar el usuario y la contraseña
session_start();  // Asegúrate de llamar session_start() al inicio
$_SESSION['usuario'] = $usuarioDB['usuario']; // Guarda el nombre del usuario
$_SESSION['dni'] = $usuarioDB['dni'];  // Guarda el DNI del usuario en la sesion
?>
