<?php
// Primero, verifica si la cookie de contador de visitas ya existe
if(isset($_COOKIE['visitas'])) {
    // Si la cookie existe, incrementa su valor
    $visitas = $_COOKIE['visitas'] + 1;
} else {
    // Si la cookie no existe, inicia el contador
    $visitas = 1;
}
//Establece o actualiza la cookie 'visitas' con el nuevo valor 
// Establece la cookie, con un tiempo de expiración de 30 días
//El parámetro "/" indica que la cookie está disponible en todo el sitio.
setcookie('visitas', $visitas, time() + (86400 * 30), "/");



?>
