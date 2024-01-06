<?php


// Cerrar sesión
if (isset($_GET['cerrar_sesion']) && $_GET['cerrar_sesion'] == '1') {
    session_unset();
    session_destroy();
    header('Location: principal.php?sesion_cerrada=exitosa');
    exit();
}

// Redirección si no hay un usuario logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit(); 
}

$usuarioId = $_SESSION['usuario_id']; 
$usuarioEmail = $_SESSION['usuario_email'] ?? 'No identificado';

?>