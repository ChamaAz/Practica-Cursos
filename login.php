<?php
//UN FORMULARIO PARA COGER DATOS
//despues de verificar el usuario y el pass
session_start();
$_SESSION['usuario'] = $usuarioDB['usuario']; //guarda el nombre del usuario
$_SESSION['dni'] = $usuarioDB['dni'];  //guarda el DNI del usuario en la sesion
?>
