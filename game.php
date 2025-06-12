<?php
// game.php
session_start(); // Inicia la sesión para poder acceder al ID del usuario loggeado
include 'config/db.php'; // Incluye el archivo de conexión a la base de datos

// Establece el encabezado de la respuesta HTTP para indicar que el contenido es JSON
// Esto es crucial para que JavaScript pueda parsear la respuesta correctamente.
header('Content-Type: application/json');

// Inicializa un array para la respuesta que se enviará de vuelta al cliente
// Por defecto, se asume que no hay éxito y se proporciona un mensaje.
$response = ['success' => false, 'message' => ''];

// 1. Verificar si el usuario está loggeado
// Si el usuario no está en sesión, no se le permite registrar victorias.
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    $response['message'] = 'No estás loggeado. No se puede registrar la victoria.';
    echo json_encode($response); // Envía la respuesta JSON y termina el script
    exit();
}

// 2. Procesar la petición POST
// Solo se procesarán las peticiones que sean de tipo POST y que contengan el parámetro 'action'.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action']; // Obtiene el valor del parámetro 'action'

    // 3. Manejar la acción 'record_win'
    // Esta acción se envía desde JavaScript cuando un jugador gana.
    if ($action === 'record_win') {
        $winner_marker = $_POST['winner'] ?? ''; // Obtiene el marcador del ganador ('X' u 'O')
        $user_id = $_SESSION['user_id']; // Obtiene el ID del usuario loggeado desde la sesión

        // CONSIDERACIÓN IMPORTANTE PARA ESTE DEMO:
        // En esta implementación simplificada, asumimos que el jugador loggeado es siempre 'X'.
        // Si el juego fuera realmente de dos jugadores en línea o contra una IA,
        // la lógica del servidor debería ser más robusta:
        // - El servidor debería validar el movimiento y determinar el ganador.
        // - Se debería enviar el ID del ganador real (no solo el marcador 'X' u 'O')
        //   para actualizar el puntaje del jugador correcto.
        if ($winner_marker === 'X') { // Si el ganador reportado es 'X'
            // Prepara la consulta SQL para actualizar el número de victorias del usuario
            // Se usa una sentencia preparada para prevenir inyección SQL.
            $stmt = $conexion->prepare("UPDATE usuarios SET victorias = victorias + 1 WHERE id = ?");
            // Vincula el ID del usuario como un entero ('i')
            $stmt->bind_param("i", $user_id);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                $response['success'] = true; // Marca la operación como exitosa
                $response['message'] = 'Puntaje actualizado correctamente.';
            } else {
                // Si hay un error en la ejecución de la consulta
                $response['message'] = 'Error al actualizar puntaje en la base de datos: ' . $stmt->error;
            }
            $stmt->close(); // Cierra la sentencia preparada
        } else {
            // Si el ganador es 'O', en este demo simplificado, no se actualiza el puntaje del usuario loggeado.
            // Aún así, la operación es "exitosa" en el sentido de que no hubo un error en la lógica del servidor.
            $response['message'] = 'Victoria registrada solo para el jugador X (por simplificación del demo).';
            $response['success'] = true; // Indicar éxito si no hay acción requerida para 'O'
        }
    } else {
        // Si la acción enviada no es reconocida
        $response['message'] = 'Acción desconocida.';
    }
} else {
    // Si la petición no es POST o no contiene la acción requerida
    $response['message'] = 'Petición inválida o falta el parámetro de acción.';
}

$conexion->close(); // Cierra la conexión a la base de datos al finalizar el script
echo json_encode($response); // Envía la respuesta JSON al cliente
exit(); // Termina la ejecución del script
?>