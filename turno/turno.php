<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Turnos</title>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <form action="generar_turnos.php" method="post" id="turnoForm">
        <h1>Generador de Turnos</h1>

        <div class="selector-fecha">
            <label for="mes">Mes:</label>
            <select id="mes" name="mes" required>
                <?php
                $mesActual = date("n");
                $nombresMeses = [
                    1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio",
                    7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
                ];

                for ($mes = 1; $mes <= 12; $mes++) {
                    echo "<option value=\"$mes\"";
                    if ($mes == $mesActual) {
                        echo " selected";
                    }
                    echo ">" . $nombresMeses[$mes] . "</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <label for="anio">Año:</label>
            <select name="anio" required>
                <?php
                $anioActual = date("Y");
                for ($anio = 2000; $anio <= 2030; $anio++) {
                    echo "<option value=\"$anio\"";
                    if ($anio == $anioActual) {
                        echo " selected";
                    }
                    echo ">$anio</option>";
                }
                ?>
            </select>
        </div>
        <div class="contenedor-calendario">
            <div class="navegacion-calendario">

            </div>

            <div class="dias-semana">
               <hr> <div >DIAS</div><hr>

            </div>
            <div class="calendario" id="calendario"></div>

            <div class="titulo-horas"> <br> Horarios Disponibles</div>
            <div class="horas" id="horas"></div>

            <button type="submit">Generar Turnos</button>
            <input type="hidden" id="fechaSeleccionada" name="fechaSeleccionada" value="">
            <input type="hidden" id="horasSeleccionadas" name="horasSeleccionadas" value="">
        </div>
        <br>

    </form>
   

       
    <script>
        const anio = new Date().getFullYear();
        const mes = new Date().getMonth() + 1;
        const diasSemana = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        const fechaActual = new Date();
        const diasEnMes = new Date(anio, mes, 0).getDate(); // Obtener el número de días en el mes

        const contenedorDias = document.getElementById('calendario'); // Cambiado de 'contenedorDias' a 'calendario'

        for (let dia = 1; dia <= diasEnMes; dia++) {
            const diaElemento = document.createElement('div');
            diaElemento.classList.add('dia', 'disponible');
            diaElemento.textContent = dia;

            const fecha = new Date(anio, mes - 1, dia);
            const diaSemana = diasSemana[fecha.getDay()];

            // Verificar si es sábado o domingo y marcar como no disponible
            if (diaSemana === 'Sab' || diaSemana === 'Dom') {
                diaElemento.classList.remove('disponible');
                diaElemento.classList.add('no-disponible');
            }
            diaElemento.addEventListener('click', () => {
                document.querySelectorAll('.dia').forEach(elemento => {
                    elemento.classList.remove('ocupado', 'disponible', 'seleccionado');
                    elemento.classList.add('disponible');
                });

                diaElemento.classList.add('seleccionado');
                document.getElementById('fechaSeleccionada').value = diaElemento.textContent;

                // Limpiar las horas al cambiar el día
                document.getElementById('horas').innerHTML = '';

                const horasDisponibles = [
                    '08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00',
                    '18:00 - 19:00', '19:00 - 20:00', '20:00 - 21:00', '21:00 - 22:00', '22:00 - 23:00', '23:00 - 00:00'
                ];

                horasDisponibles.forEach(hora => {
                    const horaElemento = document.createElement('div');
                    horaElemento.classList.add('hora', 'disponible');
                    horaElemento.textContent = hora;

                    // Simular algunas horas ocupadas (debes obtener esta información de tu base de datos)
                    if (dia % 2 === 0) {
                        horaElemento.classList.remove('disponible');
                        horaElemento.classList.add('ocupada');
                    }

                    horaElemento.addEventListener('click', () => {
                        horaElemento.classList.toggle('seleccionado');
                        actualizarHorasSeleccionadas();
                    });

                    document.getElementById('horas').appendChild(horaElemento);
                });
            });

            contenedorDias.appendChild(diaElemento);
        }

        function actualizarHorasSeleccionadas() {
            const horasSeleccionadasElementos = document.querySelectorAll('.hora.seleccionado');
            const horasSeleccionadas = Array.from(horasSeleccionadasElementos).map(horaElemento => horaElemento.textContent);

            document.getElementById('horasSeleccionadas').value = horasSeleccionadas.join(', ');
        }

        function enviarFormulario() {
            document.getElementById('turnoForm').submit();
        }
    </script>
</body>

</html>
