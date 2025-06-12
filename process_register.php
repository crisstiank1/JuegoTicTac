<?php
// process_register.php
session_start(); // Inicia la sesión al principio
include 'config/db.php'; // Incluye la conexión a la base de datos

// Solo procesar si la petición es POST (cuando se envía el formulario)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // La contraseña se hasheará, no se limpia con trim directamente

    // --- Validaciones de entrada ---

    // 1. Campos obligatorios
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['message'] = "Todos los campos son obligatorios.";
        $_SESSION['message_type'] = "danger"; // Clase CSS para alert-danger (rojo)
        header("Location: register.php"); // Redirigir de vuelta al formulario
        exit();
    }

    // 2. Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "El formato del correo electrónico no es válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: register.php");
        exit();
    }

    // 3. Hashear la contraseña de forma segura
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // --- Verificación de unicidad en la base de datos ---

    // Verificar si el nombre de usuario o el correo electrónico ya existen
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email); // 'ss' indica que ambos parámetros son strings
    $stmt->execute();
    $stmt->store_result(); // Almacenar los resultados para poder usar num_rows

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "El nombre de usuario o correo electrónico ya están registrados.";
        $_SESSION['message_type'] = "danger";
        header("Location: register.php");
        exit();
    }
    $stmt->close(); // Cerrar la sentencia preparada anterior

    // --- Insertar el nuevo usuario en la base de datos ---

    $stmt = $conexion->prepare("INSERT INTO usuarios (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash); // 'sss' para 3 strings

    if ($stmt->execute()) {
        $_SESSION['message'] = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        $_SESSION['message_type'] = "success"; // Clase CSS para alert-success (verde)
        header("Location: login.php"); // Redirigir a la página de inicio de sesión
        exit();
    } else {
        // En caso de error en la inserción (poco probable si las validaciones pasaron)
        $_SESSION['message'] = "Error al registrar el usuario: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
        header("Location: register.php");
        exit();
    }

    $stmt->close(); // Cerrar la sentencia preparada
    $conexion->close(); // Cerrar la conexión a la base de datos
} else {
    // Si alguien intenta acceder a process_register.php directamente sin POST
    header("Location: register.php");
    exit();
}
?>