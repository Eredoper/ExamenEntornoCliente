<?php
// Inicializar variables para almacenar el email y la contraseña del usuario
$email = $password = '';
$errores = []; // Array para almacenar mensajes de error

// Intento de conexión a la base de datos utilizando PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc_biblioteca', 'root', '1234');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    // Si hay un error en la conexión, mostrar el mensaje y terminar la ejecución
    exit("Error de conexión: " . $error->getMessage());
}

// Procesar el formulario cuando se envía mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el email ya está registrado en la base de datos
    $consulta = $pdo->prepare("SELECT email FROM usuarios WHERE email = :email");
    $consulta->bindParam(':email', $email);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        // Si el email ya está registrado, agregar un mensaje de error
        $errores[] = "El email ya está registrado.";
    }

    // Si no hay errores, proceder con el registro del usuario
    if (empty($errores)) {
        // Encriptar la contraseña antes de almacenarla en la base de datos
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Preparar y ejecutar la consulta SQL para registrar al nuevo usuario
        $consulta = $pdo->prepare("INSERT INTO usuarios (email, password) VALUES (:email, :password)");
        $consulta->bindParam(':email', $email);
        $consulta->bindParam(':password', $passwordHash);

        if ($consulta->execute()) {
            // Si el usuario se registra con éxito, redireccionar a la página principal
            header('Location: principal.php?registro=exitoso');
            exit();
        } else {
            // En caso de un error en la inserción, agregar un mensaje de error
            $errores[] = "Error al guardar en la base de datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-400 to-green-500 h-screen flex items-center justify-center">
    <div class="flex flex-col items-center justify-center h-full">
        <!-- Formulario de registro -->
        <form action="registro.php" method="post" class="max-w-md w-full mx-auto bg-white p-6 rounded shadow">
            <!-- Campo para el email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <!-- Campo para la contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <!-- Botones de acción -->
            <div class="flex items-center justify-between mt-4">
                <input type="submit" value="Enviar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <a href="registro.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Borrar</a>
            </div>
        </form>
        <!-- Sección para mostrar errores si existen -->
        <?php if (!empty($errores)): ?>
            <div class="mt-4 max-w-md w-full mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                <strong class='font-bold'>Errores encontrados:</strong>
                <ul class='list-disc list-inside'>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
