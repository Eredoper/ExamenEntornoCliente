<?php
// Configuración de la conexión a la base de datos


$host = 'localhost'; //Host de la BBDD
$dbname = 'mvc_biblioteca';//Nombre de la BBDD a la que queremos conectarnos. 
$user = 'root';//Nombre de usuario y contraseña para acceder a la BBDD
$password = '1234';

//Intento de conexion a la base de datos
try {
    //Crear un nuevo objeto PDO para la conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    //Configuramos PDO por si tenemos que lanzar la excepcion en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    //Si la conexion falla . termina la ejecución y mostramos el mensaje
    exit("Error de conexión: " . $e->getMessage());
}

?>