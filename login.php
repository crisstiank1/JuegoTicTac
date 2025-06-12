<?php
session_start(); // Inicia la sesión para poder usar mensajes flash
include 'includes/header.php'; // Incluye el encabezado
?>

<div class="container">
    <h2>Inicio de Sesión</h2>
    <?php
    // Mostrar mensajes de éxito o error si existen en la sesión
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '" role="alert">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
        unset($_SESSION['message_type']); // Limpiar el tipo de mensaje
    }
    ?>
    <form action="process_login.php" method="POST">
        <div class="mb-3">
            <label for="username_email" class="form-label">Nombre de Usuario o Correo:</label>
            <input type="text" class="form-control" id="username_email" name="username_email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
    <p class="mt-3">¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
</div>

<?php include 'includes/footer.php'; // Incluye el pie de página ?>