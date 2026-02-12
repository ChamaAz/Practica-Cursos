
<?php
session_start();
session_destroy(); // Destruye la sesion
header('Location: index.php'); // Redirige al login
exit();
?>
