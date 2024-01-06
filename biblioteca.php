<?php
require_once 'conexion.php';
require_once 'verificarSesion.php'; 
require_once 'cerrarSesion.php';
require_once 'anadirlibro.php';
require_once 'eliminarLibro.php';
require_once 'actualizarLibro.php';
require_once 'filtro.php';
?>

<!-- Inicio del código HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-400 to-green-500 min-h-screen">
    <div class="absolute top-0 right-0 p-4">
        <p class="text-black font-bold italic text-xl">
            <?php echo htmlspecialchars($usuarioEmail); ?>
            <span class="text-green-500">Conectado</span>
        </p>
        <a href="biblioteca.php?cerrar_sesion=1" class="text-red-600 hover:text-red-800 ml-4">Cerrar sesión</a>
    </div>

    <!-- Selector para filtrar libros -->
<div class="absolute top-0 left-0 ml-10 mt-6">
    <form action="biblioteca.php" method="get" class="bg-transparent">
        <label for="filtro" class="block text-gray-900 text-xl font-bold mb-4">Mostrar:</label>
        <select name="filtro" id="filtro" onchange="this.form.submit()" class="shadow border rounded py-2 px-3 text-gray-700 bg-transparent">
            <option value="todos" <?php echo $filtro == 'todos' ? 'selected' : ''; ?>>Todos los libros</option>
            <option value="leidos" <?php echo $filtro == 'leidos' ? 'selected' : ''; ?>>Leídos</option>
            <option value="no_leidos" <?php echo $filtro == 'no_leidos' ? 'selected' : ''; ?>>No Leídos</option>
            <option value="favoritos" <?php echo $filtro == 'favoritos' ? 'selected' : ''; ?>>Favoritos</option>
            <option value="orden_az" <?php echo $filtro == 'orden_az' ? 'selected' : ''; ?>>Orden Alfabético A-Z</option>
            <option value="orden_za" <?php echo $filtro == 'orden_za' ? 'selected' : ''; ?>>Orden Alfabético Z-A</option>
        </select>
    </form>
</div>

    <div class="container mx-auto px-4 py-20">
        <div class="text-center mb-8">
            <h1 class="text-6xl font-bold mb-6 italic text-white">📚 Tu Biblioteca Personal</h1>
            
            <!-- Formulario para añadir libros -->
            <form action="biblioteca.php" method="post" class="max-w-md w-full mx-auto bg-white p-6 rounded shadow">
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título del libro:</label>
                    <input type="text" id="titulo" name="titulo" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label for="autor" class="block text-gray-700 text-sm font-bold mb-2">Autor:</label>
                    <input type="text" id="autor" name="autor" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label for="genero" class="block text-gray-700 text-sm font-bold mb-2">Género:</label>
                    <input type="text" id="genero" name="genero" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label for="leido" class="block text-gray-700 text-sm font-bold mb-2">Leído:</label>
                    <input type="checkbox" id="leido" name="leido" class="shadow border rounded py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label for="favorito" class="block text-gray-700 text-sm font-bold mb-2">Favorito:</label>
                    <input type="checkbox" id="favorito" name="favorito" class="shadow border rounded py-2 px-3 text-gray-700">
                </div>
                <input type="submit" value="Añadir Libro" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </form>
        </div>

        <!-- Mostramos los libros de la bbdd -->
        <div class="container mx-auto px-4 py-20">
            <div class="flex flex-wrap -mx-2">
                <?php
                while ($libro = $consulta->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='w-full sm:w-1/2 md:w-1/4 px-2 mb-4'>";
                    echo "<div class='bg-white p-4 rounded shadow'>";
                    echo "<p class='text-gray-700 text-lg'>{$libro['titulo']} - {$libro['autor']} - {$libro['genero']}</p>";
                    echo "<p class='text-gray-600'>Leído: " . ($libro['leido'] ? 'Sí' : 'No') . " - Favorito: " . ($libro['favorito'] ? 'Sí' : 'No') . "</p>";
                    echo "<div class='flex justify-between mt-4'>";
                    echo "<a href='biblioteca.php?editar_libro={$libro['id_libro']}' class='text-blue-600 hover:text-blue-800'>Editar</a>";
                    echo "<a href='biblioteca.php?eliminar_libro={$libro['id_libro']}' class='text-red-600 hover:text-red-800'>Eliminar</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>


        <!-- Formulario para editar libros -->
        <?php if ($libroParaEditar): ?>
        <form action="biblioteca.php" method="post" class="max-w-md w-full mx-auto bg-white p-6 rounded shadow">
            <input type="hidden" name="id_libro_editar" value="<?php echo $libroParaEditar['id_libro']; ?>">

            <div class="mb-4">
                <label for="titulo_editar" class="block text-gray-700 text-sm font-bold mb-2">Título del libro:</label>
                <input type="text" id="titulo_editar" name="titulo_editar" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?php echo htmlspecialchars($libroParaEditar['titulo']); ?>">
            </div>
            <div class="mb-4">
                <label for="autor_editar" class="block text-gray-700 text-sm font-bold mb-2">Autor:</label>
                <input type="text" id="autor_editar" name="autor_editar" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?php echo htmlspecialchars($libroParaEditar['autor']); ?>">
            </div>
            <div class="mb-4">
                <label for="genero_editar" class="block text-gray-700 text-sm font-bold mb-2">Género:</label>
                <input type="text" id="genero_editar" name="genero_editar" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?php echo htmlspecialchars($libroParaEditar['genero']); ?>">
            </div>
            <div class="mb-4">
                <label for="leido_editar" class="block text-gray-700 text-sm font-bold mb-2">Leído:</label>
                <input type="checkbox" id="leido_editar" name="leido_editar" <?php if (!empty($libroParaEditar['leido'])) echo 'checked'; ?> class="shadow border rounded py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="favorito_editar" class="block text-gray-700 text-sm font-bold mb-2">Favorito:</label>
                <input type="checkbox" id="favorito_editar" name="favorito_editar" <?php if (!empty($libroParaEditar['favorito'])) echo 'checked'; ?> class="shadow border rounded py-2 px-3 text-gray-700">
            </div>
            <input type="submit" value="Guardar Cambios" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </form>
        <?php endif; ?>

    </div>
<footer class="bg-gray-800 text-white text-center p-4 mt-8">
    <p>Eduardo Redondo Pérez - Examen de Desarrollo Web en Entorno Servidor</p>
    <div class="flex justify-center space-x-4 mt-2">
        <a href="https://github.com/tu-usuario/tu-repositorio" target="_blank" class="inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 50 50">
    <path d="M17.791,46.836C18.502,46.53,19,45.823,19,45v-5.4c0-0.197,0.016-0.402,0.041-0.61C19.027,38.994,19.014,38.997,19,39 c0,0-3,0-3.6,0c-1.5,0-2.8-0.6-3.4-1.8c-0.7-1.3-1-3.5-2.8-4.7C8.9,32.3,9.1,32,9.7,32c0.6,0.1,1.9,0.9,2.7,2c0.9,1.1,1.8,2,3.4,2 c2.487,0,3.82-0.125,4.622-0.555C21.356,34.056,22.649,33,24,33v-0.025c-5.668-0.182-9.289-2.066-10.975-4.975 c-3.665,0.042-6.856,0.405-8.677,0.707c-0.058-0.327-0.108-0.656-0.151-0.987c1.797-0.296,4.843-0.647,8.345-0.714 c-0.112-0.276-0.209-0.559-0.291-0.849c-3.511-0.178-6.541-0.039-8.187,0.097c-0.02-0.332-0.047-0.663-0.051-0.999 c1.649-0.135,4.597-0.27,8.018-0.111c-0.079-0.5-0.13-1.011-0.13-1.543c0-1.7,0.6-3.5,1.7-5c-0.5-1.7-1.2-5.3,0.2-6.6 c2.7,0,4.6,1.3,5.5,2.1C21,13.4,22.9,13,25,13s4,0.4,5.6,1.1c0.9-0.8,2.8-2.1,5.5-2.1c1.5,1.4,0.7,5,0.2,6.6c1.1,1.5,1.7,3.2,1.6,5 c0,0.484-0.045,0.951-0.11,1.409c3.499-0.172,6.527-0.034,8.204,0.102c-0.002,0.337-0.033,0.666-0.051,0.999 c-1.671-0.138-4.775-0.28-8.359-0.089c-0.089,0.336-0.197,0.663-0.325,0.98c3.546,0.046,6.665,0.389,8.548,0.689 c-0.043,0.332-0.093,0.661-0.151,0.987c-1.912-0.306-5.171-0.664-8.879-0.682C35.112,30.873,31.557,32.75,26,32.969V33 c2.6,0,5,3.9,5,6.6V45c0,0.823,0.498,1.53,1.209,1.836C41.37,43.804,48,35.164,48,25C48,12.318,37.683,2,25,2S2,12.318,2,25 C2,35.164,8.63,43.804,17.791,46.836z"></path>
</svg>
        </a>
        <a href="https://drive.google.com/drive/folders/tu-carpeta" target="_blank" class="inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48">
            <path fill="#1e88e5" d="M38.59,39c-0.535,0.93-0.298,1.68-1.195,2.197C36.498,41.715,35.465,42,34.39,42H13.61 c-1.074,0-2.106-0.285-3.004-0.802C9.708,40.681,9.945,39.93,9.41,39l7.67-9h13.84L38.59,39z"></path><path fill="#fbc02d" d="M27.463,6.999c1.073-0.002,2.104-0.716,3.001-0.198c0.897,0.519,1.66,1.27,2.197,2.201l10.39,17.996 c0.537,0.93,0.807,1.967,0.808,3.002c0.001,1.037-1.267,2.073-1.806,3.001l-11.127-3.005l-6.924-11.993L27.463,6.999z"></path><path fill="#e53935" d="M43.86,30c0,1.04-0.27,2.07-0.81,3l-3.67,6.35c-0.53,0.78-1.21,1.4-1.99,1.85L30.92,30H43.86z"></path><path fill="#4caf50" d="M5.947,33.001c-0.538-0.928-1.806-1.964-1.806-3c0.001-1.036,0.27-2.073,0.808-3.004l10.39-17.996 c0.537-0.93,1.3-1.682,2.196-2.2c0.897-0.519,1.929,0.195,3.002,0.197l3.459,11.009l-6.922,11.989L5.947,33.001z"></path><path fill="#1565c0" d="M17.08,30l-6.47,11.2c-0.78-0.45-1.46-1.07-1.99-1.85L4.95,33c-0.54-0.93-0.81-1.96-0.81-3H17.08z"></path><path fill="#2e7d32" d="M30.46,6.8L24,18L17.53,6.8c0.78-0.45,1.66-0.73,2.6-0.79L27.46,6C28.54,6,29.57,6.28,30.46,6.8z"></path>
            </svg>
        </a>
    </div>
</footer>



</body>
