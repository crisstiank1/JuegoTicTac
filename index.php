<?php
session_start(); // Inicia la sesión al principio
include 'includes/header.php'; // Incluye el encabezado
include 'config/db.php'; // Incluye la conexión a la base de datos

// Obtener puntajes de los usuarios para la tabla de clasificación
$puntajes = [];
// Selecciona el username y las victorias, ordena por victorias de forma descendente y limita a 5
$result = $conexion->query("SELECT username, victorias FROM usuarios ORDER BY victorias DESC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $puntajes[] = $row;
    }
}
?>


<div class="container mt-4">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
        <h2 class="mb-4">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>! A jugar Tic Tac Toe</h2>

        <div class="row">
            <div class="col-md-6">
                <h3>Tu turno: <span id="current-player">X</span></h3>
                <div id="board" class="tic-tac-toe-board">
                    <div class="cell" data-index="0"></div>
                    <div class="cell" data-index="1"></div>
                    <div class="cell" data-index="2"></div>
                    <div class="cell" data-index="3"></div>
                    <div class="cell" data-index="4"></div>
                    <div class="cell" data-index="5"></div>
                    <div class="cell" data-index="6"></div>
                    <div class="cell" data-index="7"></div>
                    <div class="cell" data-index="8"></div>
                </div>
                <button id="reset-button" class="btn btn-warning mt-3">Reiniciar Juego</button>
                <div id="game-message" class="mt-3 alert alert-info" style="display: none;"></div>
            </div>
            <div class="col-md-6">
                <h3>Tabla de Clasificación (Top 5)</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Victorias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($puntajes)): ?>
                            <tr><td colspan="2">No hay puntajes aún.</td></tr>
                        <?php else: ?>
                            <?php foreach ($puntajes as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['username']); ?></td>
                                    <td><?php echo $p['victorias']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <div class="jumbotron text-center">
            <h1>Bienvenido al Tic Tac Toe Online</h1>
            <p class="lead">Inicia sesión o regístrate para jugar y competir por los mejores puntajes.</p>
            <a href="login.php" class="btn btn-primary btn-lg">Iniciar Sesión</a>
            <a href="register.php" class="btn btn-secondary btn-lg">Registrarse</a>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
<script src="js/game.js"></script>
<?php endif; ?>

<?php
// Cerrar la conexión a la base de datos al final del script
$conexion->close();
include 'includes/footer.php'; // Incluye el pie de página
?>