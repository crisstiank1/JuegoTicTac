// js/game.js

// Obtener referencias a los elementos del DOM
const board = document.getElementById('board'); // El contenedor del tablero
const cells = document.querySelectorAll('.cell'); // Todas las celdas individuales
const resetButton = document.getElementById('reset-button'); // Botón para reiniciar el juego
const currentPlayerDisplay = document.getElementById('current-player'); // Elemento que muestra el turno actual (X u O)
const gameMessage = document.getElementById('game-message'); // Elemento para mostrar mensajes del juego (ganador, empate)

// Variables de estado del juego
let currentPlayer = 'X'; // El jugador actual, siempre inicia con 'X'
let gameBoard = ['', '', '', '', '', '', '', '', '']; // Un array que representa el estado lógico del tablero (9 celdas)
let gameActive = true; // Booleano que indica si el juego está activo (true) o ha terminado (false)

// Definición de las condiciones ganadoras
// Son combinaciones de índices del array 'gameBoard' que resultan en una victoria
const winningConditions = [
    [0, 1, 2], // Fila superior
    [3, 4, 5], // Fila media
    [6, 7, 8], // Fila inferior
    [0, 3, 6], // Columna izquierda
    [1, 4, 7], // Columna central
    [2, 5, 8], // Columna derecha
    [0, 4, 8], // Diagonal principal (\)
    [2, 4, 6]  // Diagonal secundaria (/)
];

// Función que se ejecuta cada vez que el usuario hace clic en una celda del tablero
function handleCellClick(e) {
    const clickedCell = e.target; // El elemento de la celda en la que se hizo clic
    // Obtener el índice de la celda (del 0 al 8) a partir del atributo 'data-index'
    const clickedCellIndex = parseInt(clickedCell.getAttribute('data-index'));

    // Validaciones:
    // 1. Si la celda ya está ocupada (no es una cadena vacía en gameBoard)
    // 2. O si el juego ya no está activo (gameActive es false)
    // En cualquiera de estos casos, no se permite el movimiento y la función termina.
    if (gameBoard[clickedCellIndex] !== '' || !gameActive) {
        return;
    }

    // Si el movimiento es válido, se procede a realizarlo
    makeMove(clickedCell, clickedCellIndex);
}

// Función para registrar el movimiento en el tablero (lógico y visual)
function makeMove(cell, index) {
    gameBoard[index] = currentPlayer; // Actualiza el array lógico del tablero con la marca del jugador actual
    cell.innerHTML = currentPlayer;    // Muestra la marca (X o O) en la celda HTML
    // Añade una clase CSS ('x' o 'o') a la celda para aplicar estilos específicos de color
    cell.classList.add(currentPlayer.toLowerCase());

    checkResult(); // Después de cada movimiento, se verifica si hay un ganador o un empate

    // Si el juego aún está activo después de verificar el resultado, se cambia de turno
    if (gameActive) {
        // Alterna el jugador actual: si era 'X', ahora es 'O'; si era 'O', ahora es 'X'
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        currentPlayerDisplay.innerHTML = currentPlayer; // Actualiza el texto en la interfaz que indica el turno
    }
}

// Función principal para verificar el estado del juego: ganador o empate
function checkResult() {
    let roundWon = false; // Variable para saber si alguien ganó en esta ronda

    // Iterar sobre todas las posibles condiciones ganadoras
    for (let i = 0; i < winningConditions.length; i++) {
        const winCondition = winningConditions[i]; // Obtiene una combinación ganadora (ej: [0, 1, 2])
        // Obtiene los valores de las tres celdas de la condición actual
        let a = gameBoard[winCondition[0]];
        let b = gameBoard[winCondition[1]];
        let c = gameBoard[winCondition[2]];

        // Si alguna de las celdas está vacía, esta condición no puede ser ganadora aún, se salta a la siguiente
        if (a === '' || b === '' || c === '') {
            continue;
        }
        // Si las tres celdas tienen el mismo valor (X, X, X o O, O, O), significa que hay un ganador
        if (a === b && b === c) {
            roundWon = true; // Se marca que hay un ganador
            break; // Se sale del bucle, ya que encontramos una condición ganadora
        }
    }

    // Si se encontró un ganador
    if (roundWon) {
        gameMessage.style.display = 'block'; // Hace visible el mensaje de juego
        gameMessage.className = 'mt-3 alert alert-success'; // Establece la clase CSS para un mensaje de éxito (verde)
        gameMessage.innerHTML = `¡El jugador ${currentPlayer} ha ganado!`; // Muestra el mensaje del ganador
        gameActive = false; // El juego termina
        board.classList.add('game-over'); // Añade una clase al tablero para deshabilitar más clics (CSS)

        // IMPORTANTE: Enviar la victoria al servidor para actualizar los puntajes
        // Aquí asumimos que 'X' es el jugador loggeado.
        // En un juego de 2 jugadores en línea, necesitarías más lógica para identificar al ganador real.
        if (currentPlayer === 'X') {
            sendGameResult(currentPlayer);
        }
        return; // Termina la función
    }

    // Si no hay ganador, verificar si es un empate
    // Un empate ocurre si no hay ganador y todas las celdas están llenas (ninguna es '')
    let roundDraw = !gameBoard.includes(''); // true si gameBoard no contiene cadenas vacías
    if (roundDraw) {
        gameMessage.style.display = 'block';
        gameMessage.className = 'mt-3 alert alert-info'; // Clase para un mensaje de información (azul)
        gameMessage.innerHTML = '¡Empate!';
        gameActive = false; // El juego termina
        board.classList.add('game-over');
        return; // Termina la función
    }
}

// Función para reiniciar el estado del juego a su configuración inicial
function resetGame() {
    gameBoard = ['', '', '', '', '', '', '', '', '']; // Limpia el tablero lógico
    gameActive = true; // Activa el juego
    currentPlayer = 'X'; // Restablece el jugador actual a 'X'
    currentPlayerDisplay.innerHTML = currentPlayer; // Actualiza el display del turno
    gameMessage.style.display = 'none'; // Oculta cualquier mensaje anterior
    gameMessage.innerHTML = ''; // Limpia el contenido del mensaje
    
    // Limpia visualmente cada celda del tablero y remueve las clases de 'x' u 'o'
    cells.forEach(cell => {
        cell.innerHTML = '';
        cell.classList.remove('x', 'o');
    });
    board.classList.remove('game-over'); // Remueve la clase que deshabilita los clics del tablero
}

// Función para enviar el resultado de la partida al servidor PHP (usando Fetch API para AJAX)
function sendGameResult(winner) {
    // Solo enviamos la victoria si el ganador es 'X', ya que en este demo 'X' es el usuario loggeado.
    // Esto evita que 'O' (que podría ser una IA o el segundo jugador si se implementa)
    // actualice el puntaje del usuario loggeado.
    if (winner === 'X') { 
        fetch('game.php', { // Realiza una petición POST al script 'game.php'
            method: 'POST',
            headers: {
                // Indica que los datos se enviarán como un formulario codificado en URL
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // El cuerpo de la petición con los datos a enviar
            body: 'action=record_win&winner=' + winner // 'action' para que game.php sepa qué hacer, y 'winner'
        })
        .then(response => response.json()) // Convierte la respuesta del servidor a JSON
        .then(data => {
            // Maneja la respuesta JSON del servidor
            if (data.success) {
                console.log('Puntaje actualizado en el servidor.');
                // Recarga la página después de un breve retraso para que la tabla de puntajes se actualice visualmente
                setTimeout(() => location.reload(), 1500); 
            } else {
                console.error('Error al actualizar puntaje:', data.message);
                // Muestra un mensaje de error si el servidor indica un problema
                gameMessage.style.display = 'block';
                gameMessage.className = 'mt-3 alert alert-danger';
                gameMessage.innerHTML = `Error al registrar victoria: ${data.message}`;
            }
        })
        .catch(error => {
            // Captura errores de red o de la petición AJAX
            console.error('Error en la petición AJAX:', error);
            gameMessage.style.display = 'block';
            gameMessage.className = 'mt-3 alert alert-danger';
            gameMessage.innerHTML = 'Error de comunicación con el servidor.';
        });
    }
}

// Configuración de los Event Listeners (escuchadores de eventos)
// Itera sobre todas las celdas y añade un escuchador de clic a cada una
cells.forEach(cell => cell.addEventListener('click', handleCellClick));
// Añade un escuchador de clic al botón de reiniciar
resetButton.addEventListener('click', resetGame);

// Inicializar el juego al cargar la página
// Esto asegura que el tablero y las variables estén en su estado inicial cuando la página se carga
resetGame();