<?php
session_start(); // Inicia la sesión para poder usar mensajes flash
include 'includes/header.php'; // Incluye el encabezado
?>

<div class="container">
    <h2>Registro de Usuario</h2>
    <?php
    // Mostrar mensajes de éxito o error si existen en la sesión
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '" role="alert">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
        unset($_SESSION['message_type']); // Limpiar el tipo de mensaje
    }
    ?>
    <form action="process_register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <p class="mt-3">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</div>

<?php include 'includes/footer.php'; // Incluye el pie de página ?>