
<?php
session_start();//imortante
session_destroy(); // Destruye la sesion
header('Location: index.php'); // Redirige al login
exit();
?>
