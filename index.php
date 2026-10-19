<?php
$host     = 'localhost';
$port     = 8889;
$dbname   = 'f-web';
$user     = 'root';
$password = 'root';

// Errores de mysqli como excepciones en vez de warnings silenciosos.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $dbname, $port);
} catch (mysqli_sql_exception $e) {
    error_log('Error de conexión: ' . $e->getMessage());
    http_response_code(500);
    die('No se pudo conectar a la base de datos. Intenta de nuevo más tarde.');
}

$conn->query("CREATE TABLE IF NOT EXISTS `formulario` (
    `id`       INT AUTO_INCREMENT PRIMARY KEY,
    `nombre`   VARCHAR(300) NOT NULL,
    `Teléfono` VARCHAR(300) NOT NULL,
    `Email`    VARCHAR(300) NOT NULL,
    `Mensaje`  VARCHAR(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['Email'] ?? '');
    $mensaje  = trim($_POST['Mensaje'] ?? '');

    // Validación del lado del servidor: el formulario puede llegar sin pasar
    // por el HTML (curl, un bot, JS deshabilitado), así que no basta con
    // los atributos `required` del lado del cliente.
    $errores = [];
    if ($nombre === '' || $telefono === '' || $email === '' || $mensaje === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no tiene un formato válido.';
    }

    if (!empty($errores)) {
        http_response_code(400);
        echo implode(' ', array_map('htmlspecialchars', $errores));
        $conn->close();
        exit;
    }

    try {
        // Sentencia preparada: los valores nunca se concatenan en el SQL.
        $stmt = $conn->prepare(
            'INSERT INTO `formulario` (`nombre`, `Teléfono`, `Email`, `Mensaje`) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('ssss', $nombre, $telefono, $email, $mensaje);
        $stmt->execute();
        $stmt->close();

        echo "<script>
            alert('¡Datos guardados con éxito en la tabla formulario!');
            window.location.href = 'pagina.html';
        </script>";
    } catch (mysqli_sql_exception $e) {
        // El detalle real va al log del servidor, no a la respuesta: un
        // mensaje de error de MySQL puede revelar nombres de tabla/columna.
        error_log('Error al guardar formulario: ' . $e->getMessage());
        http_response_code(500);
        echo 'No se pudo guardar tu mensaje. Intenta de nuevo más tarde.';
    }
}
$conn->close();
?>
