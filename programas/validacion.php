<?php
session_start();

// Incluir el archivo de conexión a la base de datos
include 'config/conexion.php'; // Asegúrate de que esta ruta es correcta

// Verificar si se presionó el botón de login y si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["btnlogin"])) {
    echo "Botón presionado<br>";

    // Obtener datos del formulario
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // Depuración: Verificar los datos recibidos (puedes quitar esto después)
    echo "Email recibido: " . htmlspecialchars($email) . "<br>";
    echo "Contraseña recibido: " . htmlspecialchars($password) . "<br>";

    // Consulta SQL preparada para validar el usuario (y su rol)
    $sql = "SELECT usuarios.*, roles.nombre_usuario_rol AS rol_nombre
            FROM usuarios
            JOIN roles ON usuarios.rol_usuario_id = roles.id_rol_usuario
            WHERE usuarios.email_usuario = ?";
    
    // Verificar si $link es válido antes de preparar la consulta
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si el usuario existe
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar la contraseña (asegúrate de usar hashing en producción)
            if ($password === $user['contrasena']) { // Esto es solo para pruebas; usa hashing en producción
                // Guardar datos del usuario en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['rol_nombre'] = $user['rol_nombre'];

                // Redirigir según el rol del usuario
                if ($user['rol_nombre'] == 'Administrador') {
                    header("Location: admin.html");
                } elseif ($user['rol_nombre'] == 'Docente') {
                    header("Location: docente.html");
                } elseif ($user['rol_nombre'] == 'Estudiante') {
                    header("Location: estudiante.html");
                } else {
                    echo "Rol no reconocido.";

                }
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "No se encontró ningún usuario con ese email.";
        }

        // Cerrar declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $link->error;
    }
} else {
    echo "Error: No se han enviado correctamente los datos.";
}

// Cerrar conexión
$link->close();
?>





