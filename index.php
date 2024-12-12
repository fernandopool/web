<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Salón de Maestro</title>
    <style>
        /* CSS para darle estilo al formulario */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333; 
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="time"],
        select {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .logout {
            text-align: center;
            margin-top: 10px;
        }
        .logout a {
            color: #007BFF;
            text-decoration: none;
        }
        .logout a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Consulta de Salón de Maestro</h2>
        <form action="consulta.php" method="post">
            <label for="nombre_maestro">Nombre del Maestro:</label>
            <input type="text" id="nombre_maestro" name="nombre_maestro" required>

            <label for="hora_actual">Hora Actual (HH:MM:SS):</label>
            <input type="text" id="hora_actual" name="hora_actual" placeholder="Ej: 14:30:00" required>

            <label for="dia_semana">Día de la Semana:</label>
            <select id="dia_semana" name="dia_semana" required>
                <option value="">Seleccione un día</option>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
            </select>

            <input type="submit" value="Consultar">
        </form>
        <div class="logout">
	 <p><a href="justificaciones.php" style="color: #007BFF; text-decoration: none;">Justificaciones</a></p>
	 <p><a href="materias.php" style="color: #007BFF; text-decoration: none;">Consulta de materias</a></p>
 <p><a href="logout.php">Cerrar sesión</a></p>
        </div>
    </div>
</body>
</html>
