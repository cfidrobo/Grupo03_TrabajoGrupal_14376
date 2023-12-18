<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Agendamiento</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>

<table class="confirmacion">
    <tr>
        <td colspan="2">
            <h1>Turno Agendado</h1>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="mensajeAgendado" class="mensaje-agendado"></div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <button onclick="window.location.href='../index.html'">Regresar</button>
        </td>
    </tr>
</table>

<?php
// Obtener datos del formulario
$fechaSeleccionada = $_POST['fechaSeleccionada'];
$horasSeleccionadas = $_POST['horasSeleccionadas'];

// Mostrar mensaje de confirmación

// Convertir $horasSeleccionadas a un array si es una cadena
if (is_string($horasSeleccionadas)) {
    $horasSeleccionadas = [$horasSeleccionadas];
}

if (!empty($horasSeleccionadas)) {
}

echo '</div>';
?>
       
    </div>
    <script>
        // Lógica para obtener la fecha y hora seleccionadas y mostrar el mensaje
        const mensajeAgendado = document.getElementById('mensajeAgendado');
        mensajeAgendado.textContent = 'Turno agendado correctamente el ';

        mensajeAgendado.textContent += `día <?php echo $fechaSeleccionada; ?> `;
        
        <?php if (!empty($horasSeleccionadas)): ?>
            mensajeAgendado.textContent += 'a las siguientes horas: ';
            <?php foreach ($horasSeleccionadas as $hora): ?>
                mensajeAgendado.textContent += '<?php echo $hora; ?>, ';
            <?php endforeach; ?>
            mensajeAgendado.textContent = mensajeAgendado.textContent.slice(0, -2); // Eliminar la última coma
        <?php endif; ?>

        // Mostrar el mensaje
        mensajeAgendado.style.display = 'block';
    </script>
</body>
</html>
