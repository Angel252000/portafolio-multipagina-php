<?php
$host     = 'localhost';
$port     = 8889;
$dbname   = 'f-web'; 
$user     = 'root';
$password = 'root';

$conn = new mysqli($host, $user, $password, $dbname, $port);
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}


$conn->query("CREATE TABLE IF NOT EXISTS `formulario` (
    `id`       INT AUTO_INCREMENT PRIMARY KEY,
    `nombre`   VARCHAR(300) NOT NULL,
    `Teléfono` VARCHAR(300) NOT NULL,
    `Email`    VARCHAR(300) NOT NULL,
    `Mensaje`  VARCHAR(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $email    = $conn->real_escape_string($_POST['Email']);
    $mensaje  = $conn->real_escape_string($_POST['Mensaje']);

    $sql = "INSERT INTO `formulario` (`nombre`, `Teléfono`, `Email`, `Mensaje`) VALUES ('$nombre', '$telefono', '$email', '$mensaje')";
    
    if ($conn->query($sql)) {
        echo "<script>
            alert('¡Datos guardados con éxito en la tabla formulario!');
            window.location.href = 'pagina.html';
        </script>";
    } else {
        echo "Error al guardar: " . $conn->error;
    }
}
$conn->close();
?>
