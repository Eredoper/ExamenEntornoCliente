<?php

session_start();
// Redirección si no hay un usuario logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit(); 
}

$usuarioId = $_SESSION['usuario_id']; 
$usuarioEmail = $_SESSION['usuario_email'] ?? 'No identificado';

?>