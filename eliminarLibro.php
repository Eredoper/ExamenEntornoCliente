<?php

// Eliminar un libro: Se ejecuta cuando se recibe la petición de eliminar un libro.
if (isset($_GET['eliminar_libro'])) {
    $id_libro = $_GET['eliminar_libro']; // Obtenemos el ID del libro a eliminar

    // Eliminamos la relación en la tabla usuarios_libro.
    $stmt = $pdo->prepare("DELETE FROM usuarios_libros WHERE id_libro = ? AND id_usuario = ?");
    $stmt->execute([$id_libro, $usuarioId]);

    // Eliminamos el libro de la tabla libros.
    $stmt = $pdo->prepare("DELETE FROM libros WHERE id_libro = ?");
    $stmt->execute([$id_libro]);

    header("Location: biblioteca.php"); // Redireccionamos a la página de la biblioteca
    exit();
}

?>