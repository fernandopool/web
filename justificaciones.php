<?php
session_start();

$host = 'localhost';
$db = 'prefectura';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Agregar Justificación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_justificacion'])) {
    $nombre_maestro = $_POST['nombre_maestro'];
    $dia_fecha = $_POST['dia_fecha'];
    $motivo = $_POST['motivo'];

    $stmt = $conn->prepare("SELECT id FROM maestros WHERE nombre = ?");
    $stmt->bind_param("s", $nombre_maestro);
    $stmt->execute();
    $stmt->bind_result($maestro_id);
    $stmt->fetch();
    $stmt->close();

    if ($maestro_id) {
        $stmt = $conn->prepare("INSERT INTO justificaciones (maestro_id, dia_fecha, motivo) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $maestro_id, $dia_fecha, $motivo);
        $stmt->execute();
        $stmt->close();

        $mensaje = "Justificación agregada con éxito.";
    } else {
        $mensaje = "No se encontró al maestro $nombre_maestro.";
    }
}

// Eliminar Justificación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_justificacion'])) {
    $justificacion_id = $_POST['justificacion_id'];

    $stmt = $conn->prepare("DELETE FROM justificaciones WHERE id = ?");
    $stmt->bind_param("i", $justificacion_id);
    $stmt->execute();
    $stmt->close();

    $mensaje = "Justificación eliminada con éxito.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Justificaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 800px;
        }
        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }
        .mensaje {
            margin: 10px 0;
            padding: 10px;
            background-color: #dff0d8;
            border: 1px solid #d0e9c6;
            border-radius: 4px;
            color: #3c763d;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            display: block;
            margin: 10px auto;
        }
        button:hover {
            background-color: #2980b9;
        }
        .justificaciones-list {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestionar Justificaciones</h2>
        <?php if (!empty($mensaje)) echo "<div class='mensaje'>$mensaje</div>"; ?>

        <!-- Formulario para agregar justificación -->
        <form method="POST">
            <input type="hidden" name="agregar_justificacion" value="1">
            <div class="form-group">
                <label for="nombre_maestro">Nombre del Maestro:</label>
                <input type="text" id="nombre_maestro" name="nombre_maestro" required>
            </div>
            <div class="form-group">
                <label for="dia_fecha">Día de la Semana:</label>
                <select id="dia_fecha" name="dia_fecha" required>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="motivo">Motivo:</label>
                <textarea id="motivo" name="motivo" rows="3" required></textarea>
            </div>
            <button type="submit">Agregar Justificación</button>
        </form>

        <!-- Formulario para eliminar justificaciones -->
        <h3>Eliminar Justificaciones</h3>
        <form method="POST">
            <div class="form-group">
                <label for="dia_eliminar">Día a consultar:</label>
                <select id="dia_eliminar" name="dia_eliminar" required>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                </select>
                <button type="submit" name="consultar_justificaciones">Consultar</button>
            </div>
        </form>

        <!-- Mostrar justificaciones para eliminar -->
        <div class="justificaciones-list">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['consultar_justificaciones'])) {
                $dia_eliminar = $_POST['dia_eliminar'];

                $query = "SELECT j.id, m.nombre, j.motivo 
                          FROM justificaciones j
                          INNER JOIN maestros m ON j.maestro_id = m.id
                          WHERE j.dia_fecha = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $dia_eliminar);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<table><thead><tr><th>Maestro</th><th>Motivo</th><th>Acción</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nombre']}</td>
                                <td>{$row['motivo']}</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='eliminar_justificacion' value='1'>
                                        <input type='hidden' name='justificacion_id' value='{$row['id']}'>
                                        <button type='submit'>Eliminar</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No se encontraron justificaciones para el día seleccionado.</p>";
                }
                $stmt->close();
            }
            ?>
        </div>

        <a href="index.php">&#8592; Regresar</a>
    </div>
</body>
</html>
