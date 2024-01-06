<?php


// Añadir un libro: Se ejecuta cuando se envía el formulario de añadir libro.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo'])) {
    // Recogemos los datos del formulario.
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $genero = $_POST['genero'];
    $leido = isset($_POST['leido']) ? 1 : 0; // Verificamos si el libro ha sido leído
    $favorito = isset($_POST['favorito']) ? 1 : 0; // Verificamos si el libro es favorito

    // Inserción del libro en la tabla libros.
    $stmt = $pdo->prepare("INSERT INTO libros (titulo, autor, genero) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $autor, $genero]);
    $id_libro = $pdo->lastInsertId(); // Obtenemos el ID del libro insertado

    // Inserción de la relación en la tabla usuarios_libro.
    $stmt = $pdo->prepare("INSERT INTO usuarios_libros (id_usuario, id_libro, leido, favorito) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuarioId, $id_libro, $leido, $favorito]);

    header("Location: biblioteca.php"); // Redireccionamos a la página de la biblioteca
    exit();
}
?>