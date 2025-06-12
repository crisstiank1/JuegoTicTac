<?php
// process_login.php
session_start(); // Inicia la sesión al principio
include 'config/db.php'; // Incluye la conexión a la base de datos

// Solo procesar si la petición es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    // Validar que los campos no estén vacíos (aunque el HTML ya tiene 'required')
    if (empty($username_email) || empty($password)) {
        $_SESSION['message'] = "Por favor, introduce tu nombre de usuario/correo y contraseña.";
        $_SESSION['message_type'] = "danger";
        header("Location: login.php");
        exit();
    }

    // Buscar el usuario por nombre de usuario o por correo electrónico
    $stmt = $conexion->prepare("SELECT id, username, password_hash FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_email, $username_email); // Se usa 'ss' porque el mismo valor se pasa dos veces
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $password_hash); // Vincula los resultados a estas variables
    $stmt->fetch(); // Obtiene la fila de resultados

    // Verificar si se encontró un usuario y si la contraseña es correcta
    if ($stmt->num_rows == 1 && password_verify($password, $password_hash)) {
        // Autenticación exitosa: Establecer variables de sesión
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true; // Flag para saber si el usuario está loggeado

        $_SESSION['message'] = "¡Bienvenido, " . htmlspecialchars($username) . "!";
        $_SESSION['message_type'] = "success";

        header("Location: index.php"); // Redirigir a la página principal (donde estará el juego)
        exit();
    } else {
        // Autenticación fallida
        $_SESSION['message'] = "Nombre de usuario/correo o contraseña incorrectos.";
        $_SESSION['message_type'] = "danger";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    // Si alguien intenta acceder a process_login.php directamente sin POST
    header("Location: login.php");
    exit();
}
?>