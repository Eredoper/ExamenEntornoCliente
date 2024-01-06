<?php
session_start(); // Iniciar la sesión o reanudarla.

$errores = []; // Array para almacenar errores.

// Comprobamos si el formulario ha sido enviado usando POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos el email y la contraseña del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Intentar conectar a la base de datos
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mvc_biblioteca', 'root', '1234');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
        exit("Error de conexión: " . $error->getMessage());
    }

    // Preparamos y ejecutamos la consulta SQL para buscar al usuario por email.
    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $consulta->bindParam(':email', $email);
    $consulta->execute();

    // Comprobamos si el usuario existe.
    if ($consulta->rowCount() > 0) {
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario']; 
            $_SESSION['usuario_email'] = $usuario['email'];
            header('Location: biblioteca.php');
            exit();
        } else {
            $errores[] = 'La contraseña es incorrecta.';
        }
    } else {   
        $errores[] = 'No se encontró el usuario.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-400 to-green-500 h-screen flex items-center justify-center">
    <div class="flex flex-col items-center justify-center h-full">
        <!--Formulario para recoger los datos del login-->
        <form action="login.php" method="post" class="max-w-md w-full mx-auto bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" value="Iniciar Sesión" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
        <!--Para mostrar errores si existen y tomar medidas.-->
        <?php if (!empty($errores)): ?>
    <div class="mt-4 max-w-md w-full mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <?php foreach ($errores as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    </div>
</body>
</html>
