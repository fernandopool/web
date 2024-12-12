<?php
session_start();
// Conectar a la base de datos
$host = 'localhost';
$db = 'prefectura';
$user = 'root'; // Cambia por tu usuario de MySQL
$pass = '12345'; // Cambia por tu contraseña de MySQL

$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el nombre del maestro y la hora actual
$nombre_maestro = $_POST['nombre_maestro'];
$hora_actual = $_POST['hora_actual'];
$dia_actual = $_POST['dia_semana']; // Obtener el día de la semana desde el formulario

// Consultar el ID del maestro según el nombre
$stmt = $conn->prepare("SELECT id FROM maestros WHERE nombre = ?");
$stmt->bind_param("s", $nombre_maestro);
$stmt->execute();
$stmt->bind_result($maestro_id);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Salón</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .mensaje {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #e9ecef;
            color: #333;
        }
        .regresar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .regresar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Consulta de Salón</h2>
    <div class="mensaje">
        <?php
        if ($maestro_id) {
            // Verificar si el maestro tiene una justificación en el día consultado
            $stmt = $conn->prepare("SELECT motivo FROM justificaciones WHERE maestro_id = ? AND dia_fecha = ?");
            $stmt->bind_param("is", $maestro_id, $dia_actual);
            $stmt->execute();
            $stmt->bind_result($motivo);
            $stmt->fetch();
            
            // Mostrar si el maestro tiene una justificación
            if ($motivo) {
                echo "El maestro $nombre_maestro no está en clase porque tiene la siguiente justificación: <strong>$motivo</strong><br>";
            } else {
                echo "El maestro $nombre_maestro no tiene justificación en este día.<br>";
            }
            $stmt->close();
            
            // Siempre verificar el salón en el horario de clase
            $stmt = $conn->prepare("SELECT salon FROM horarios WHERE maestro_id = ? AND dia_semana = ? AND hora_inicio <= ? AND hora_fin > ?");
            $stmt->bind_param("isss", $maestro_id, $dia_actual, $hora_actual, $hora_actual);
            $stmt->execute();
            $stmt->bind_result($salon);
            $stmt->fetch();

            if ($salon) {
                echo "El maestro $nombre_maestro debería estar en el salón <strong>$salon</strong>.";
            } else {
                echo "No se encontraron horarios para el maestro en este día.";
            }
            $stmt->close();

        } else {
            echo "No se encontró al maestro $nombre_maestro en el sistema.";
        }
        ?>
    </div>

    <a href="index.php" class="regresar">&#8592; Regresar</a> <!-- Flecha de regreso -->
</div>

</body>
</html>

<?php
$conn->close();
?>
