<?php
// includes/header.php
// Es importante que session_start() NO esté aquí si ya se llama en los archivos principales (index.php, login.php, etc.)
// Ya lo hemos puesto en cada archivo principal, así que este archivo debe empezar directamente con HTML.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Tic Tac Toe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php
                    // Verificar si el usuario está loggeado
                    // Necesitas que session_start() se haya ejecutado en el script que incluye este header
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']):
                    ?>
                        <li class="nav-item">
                            <span class="nav-link">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container mt-4">

<?php
// includes/footer.php
?>
    </main>
    <footer class="footer mt-auto py-3 bg-light text-center">
        <div class="container">
            <span class="text-muted">Desarrollado por [Tu Nombre/Empresa] &copy; <?php echo date("Y"); ?></span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>