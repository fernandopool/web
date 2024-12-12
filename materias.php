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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias y Maestros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            justify-content: space-between;
            margin: auto;
            max-width: 1200px;
        }
        .box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 45%;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        form {
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #e9ecef;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Consultar materias impartidas por un maestro -->
    <div class="box">
        <h2>Consultar materias impartidas por un maestro</h2>
        <form method="POST">
            <label for="nombre_maestro">Nombre del Maestro:</label>
            <input type="text" id="nombre_maestro" name="nombre_maestro" required>
            <button type="submit" name="consultar_materias">Consultar</button>
        </form>
        <div class="result">
            <?php
            if (isset($_POST['consultar_materias'])) {
                $nombre_maestro = $_POST['nombre_maestro'];

                // Consultar las materias impartidas por el maestro
                $stmt = $conn->prepare("SELECT m.nombre AS materia 
                                        FROM materias m 
                                        JOIN maestros ma ON m.maestro_id = ma.id 
                                        WHERE ma.nombre = ?");
                $stmt->bind_param("s", $nombre_maestro);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "Materias impartidas por $nombre_maestro:<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>" . $row['materia'] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "El maestro $nombre_maestro no imparte ninguna materia registrada.";
                }
                $stmt->close();
            }
            ?>
        </div>
    </div>

    <!-- Consultar qué maestro imparte una materia -->
    <div class="box">
        <h2>Consultar qué maestro imparte una materia</h2>
        <form method="POST">
            <label for="nombre_materia">Nombre de la Materia:</label>
            <input type="text" id="nombre_materia" name="nombre_materia" required>
            <button type="submit" name="consultar_maestro">Consultar</button>
        </form>
        <div class="result">
            <?php
            if (isset($_POST['consultar_maestro'])) {
                $nombre_materia = $_POST['nombre_materia'];

                // Consultar el maestro que imparte la materia
                $stmt = $conn->prepare("SELECT ma.nombre AS maestro 
                                        FROM maestros ma 
                                        JOIN materias m ON m.maestro_id = ma.id 
                                        WHERE m.nombre = ?");
                $stmt->bind_param("s", $nombre_materia);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "El maestro que imparte $nombre_materia es:<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>" . $row['maestro'] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No se encontró un maestro que imparta la materia $nombre_materia.";
                }
                $stmt->close();
            }
            ?>
	</div>

<div style="text-align: center; margin-top: 20px;">
    <a href="index.php" class="regresar" style="
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        color: white;
        background-color: #007bff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    " 
    onmouseover="this.style.backgroundColor='#0056b3'"
    onmouseout="this.style.backgroundColor='#007bff'">
        &#8592; Regresar
    </a>
</div>

        </div>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
