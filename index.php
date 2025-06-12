<?php
session_start(); // Inicia la sesión al principio de cada script que la use
include 'includes/header.php'; 
?>

    <div class="jumbotron text-center">
        <h1>Bienvenido al Tic Tac Toe Online</h1>
        <p class="lead">Inicia sesión o regístrate para jugar y competir por los mejores puntajes.</p>
        <a href="login.php" class="btn btn-primary btn-lg">Iniciar Sesión</a>
        <a href="register.php" class="btn btn-secondary btn-lg">Registrarse</a>
    </div>

<?php include 'includes/footer.php'; // Incluye el pie de página ?>