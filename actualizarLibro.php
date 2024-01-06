<?php

// Editar un libro: Cargar datos del libro para editar.
$libroParaEditar = null;
if (isset($_GET['editar_libro'])) {
    $id_libro = $_GET['editar_libro']; // Obtenemos el ID del libro a editar

    // Obtenemos los datos del libro de la tabla libros.
    $stmt = $pdo->prepare("SELECT * FROM libros WHERE id_libro = ?");
    $stmt->execute([$id_libro]);
    $libroParaEditar = $stmt->fetch(PDO::FETCH_ASSOC); // Almacenamos los datos del libro
}

// Actualizar un libro: Se ejecuta cuando se envía el formulario de edición de libro.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_libro_editar'])) {
    $id_libro = $_POST['id_libro_editar'];
    $titulo = $_POST['titulo_editar'];
    $autor = $_POST['autor_editar'];
    $genero = $_POST['genero_editar'];
    $leido = isset($_POST['leido_editar']) ? 1 : 0; // Verificamos si el libro ha sido leído
    $favorito = isset($_POST['favorito_editar']) ? 1 : 0; // Verificamos si el libro es favorito

    // Actualizamos los datos del libro en la tabla libros.
    $stmt = $pdo->prepare("UPDATE libros SET titulo = ?, autor = ?, genero = ? WHERE id_libro = ?");
    $stmt->execute([$titulo, $autor, $genero, $id_libro]);

    // Actualizamos los datos de la relación en la tabla usuarios_libro.
    $stmt = $pdo->prepare("UPDATE usuarios_libros SET leido = ?, favorito = ? WHERE id_libro = ? AND id_usuario = ?");
    $stmt->execute([$leido, $favorito, $id_libro, $usuarioId]);

    header("Location: biblioteca.php"); // Redireccionamos a la página de la biblioteca
    exit();
}


?>