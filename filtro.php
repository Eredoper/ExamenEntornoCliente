<?php
// Procesamiento del filtro
$filtro = $_GET['filtro'] ?? 'todos';
$condicionExtra = "";
$orden = "ORDER BY libros.autor, libros.titulo";

switch ($filtro) {
    case 'leidos':
        $condicionExtra = "AND usuarios_libros.leido = 1 ";
        break;
    case 'no_leidos':
        $condicionExtra = "AND usuarios_libros.leido = 0 ";
        break;
    case 'favoritos':
        $condicionExtra = "AND usuarios_libros.favorito = 1 ";
        break;
    case 'orden_az':
        $orden = "ORDER BY libros.titulo ASC ";
        break;
    case 'orden_za':
        $orden = "ORDER BY libros.titulo DESC ";
        break;
    default:
        // No hay necesidad de condición extra para 'todos'
}

// Consulta SQL para mostrar libros según el filtro
$consultaSql = "
    SELECT libros.id_libro, libros.titulo, libros.autor, libros.genero, usuarios_libros.leido, usuarios_libros.favorito
    FROM libros
    JOIN usuarios_libros ON libros.id_libro = usuarios_libros.id_libro
    WHERE usuarios_libros.id_usuario = ? $condicionExtra
    $orden
";

// Procesamiento del filtro
$filtro = $_GET['filtro'] ?? 'todos';


// Ejecución de la consulta
$consulta = $pdo->prepare($consultaSql);
$consulta->execute([$usuarioId]);

?>