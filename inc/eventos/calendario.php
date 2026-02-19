<?php

function imprimir_calendario($mes, $anio) {
    date_default_timezone_set('America/Mexico_City');
    $localizador = new IntlDateFormatter('es_Es',IntlDateFormatter::LONG, IntlDateFormatter::NONE);

    $primer_dia = mktime(0, 0, 0, $mes, 1, $anio);
    $dias_en_mes = date('t', $primer_dia);
    $dia_de_semana = date('w', $primer_dia);
    $diaActual = date('d');
    $fechaActual = date('Y-m-d');
    $objetoFecha = new DateTime($fechaActual);

    $fechaFormateada = $localizador->format($objetoFecha);
?>
    <div class='container'>
        <div class="container">
            <div class="row row-cols-md-2 justify-content-start align-items-center">
                <div class="col d-grid align-items-center" style="width:20rem;">                    
                    <h4 class='text-center text-primary'><?php echo $fechaFormateada?></h4>
                    
                </div>
                <div class="col d-flex justify-content-start p-1" style="width:20rem;">
                    <img src="<?php echo get_template_directory_uri() ?>/img/Logo-Iconos/LOGO REFORESTAMOS FONDO TRANSPARENTE.png" class="img-fluid" width="150" alt="">
                </div>
            </div>
        </div>

        <table class='w-100 shadow' style='margin-top:50px;margin-bottom:50px; border-radius:30px!important;'>
            <tr class="" style="height:50px;background-color:#D4F0D6;">
                <th class='text-center text-primary'>Domingo</th>
                <th class='text-center text-primary'>Lunes</th>
                <th class='text-center text-primary'>Martes</th>
                <th class='text-center text-primary'>Miércoles</th>
                <th class='text-center text-primary'>Jueves</th>
                <th class='text-center text-primary'>Viernes</th>
                <th class='text-center text-primary'>Sábado</th>
            </tr>
        
            <tr>
<?php


    // Rellenar celdas en blanco antes del primer día del mes
    for ($d = 0; $d < $dia_de_semana; $d++) { ?>
        <td class='m-2' style='background-color:#F0F5F2; width: 160px;'></td>
    <?php
    }

    // Rellenar celdas con días del mes
    for ($d = 1; $d <= $dias_en_mes; $d++) {?>
        
    <?php
        if($diaActual ==$d ) { ?>
            <td class='text-center text-white cursor-pointer' style='height:160px;width: 160px; border:1px solid #D4EEDE; background-color:#77C17D;'><?php echo "" . $d . "";?> </td>
    <?php } else { ?>
            <td class='text-center text-primary' style='height:160px;width: 160px; border:1px solid #D4EEDE;'><?php echo "" . $d . "";?> </td>
    <?php   }
    ?>    
    <?php

   
if (date('w', mktime(0, 0, 0, $mes, $d, $anio)) == 6) {
            echo "</tr>";

            if ($d != $dias_en_mes) {
                echo "<tr>";
            }
        }
    }

    // Rellenar celdas en blanco después del último día del mes
    while (date('w', mktime(0, 0, 0, $mes, $dias_en_mes, $anio)) != 6) { ?>
        <td class='m-2' style='background-color:#F0F5F2; width: 160px;'></td>
    <?php
        $dias_en_mes++;
    }

    echo "</tr>"
    ;
    echo "</table>";
echo "</div>";

}

// Obtener el mes y año actual
$mesActual = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
$anoActual = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

// Calcular el mes anterior y siguiente
$mesAnterior = ($mesActual == 1) ? 12 : $mesActual - 1;
$anoAnterior = ($mesActual == 1) ? $anoActual - 1 : $anoActual;
$mesSiguiente = ($mesActual == 12) ? 1 : $mesActual + 1;
$anoSiguiente = ($mesActual == 12) ? $anoActual + 1 : $anoActual;

// Obtener el nombre completo del mes actual
$nombreMes = date('F', mktime(0, 0, 0, $mesActual, 1, $anoActual));

echo "Calendario para $nombreMes $anoActual";
?>

<!-- Enlaces para cambiar entre meses -->
<a href="?mes=<?php echo $mesAnterior; ?>&ano=<?php echo $anoAnterior; ?>">Mes anterior</a>
<a href="?mes=<?php echo $mesSiguiente; ?>&ano=<?php echo $anoSiguiente; ?>">Mes siguiente</a>


<?php


// Llama a la función para imprimir el calendario del mes actual
imprimir_calendario(date('n'), date('Y'));

?>

<script>
    const meses = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const calendarContainer = document.getElementById('calendario');
    const prevButton = document.getElementById('anterior');
    const nextButton = document.getElementById('siguiente');
    const currentDate = new Date();

    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar() {
      const firstDay = new Date(currentYear, currentMonth, 1);
      const lastDay = new Date(currentYear, currentMonth + 1, 0);
      
      let calendarHTML = `<h2>${meses[currentMonth]} ${currentYear}</h2>`;
      calendarHTML += "<table class='w-100 shadow' style='margin-top:50px;margin-bottom:50px; border-radius:30px!important;'><tr style='height:50px;background-color:#D4F0D6;'>";

      // Encabezado: días de la semana
      const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
      for (let day of daysOfWeek) {
        calendarHTML += `<th class="text-center text-primary">${day}</th>`;
      }
      calendarHTML += '</tr><tr>';

      // Rellenar celdas vacías al inicio del mes
      for (let i = 0; i < firstDay.getDay(); i++) {
        calendarHTML += '<td class="m-2" style="background-color:#F0F5F2; width: 160px;"></td>';
      }

      // Días del mes
      for (let day = 1; day <= lastDay.getDate(); day++) {
        calendarHTML += `<td class='text-center text-white dias-semanas dia-${day}' style='height:160px;width: 160px; border:1px solid #D4EEDE;background-color:#77C17D;'>${day}</td>`;
        
        if ((firstDay.getDay() + day - 1) % 7 === 6) {
          calendarHTML += '</tr><tr>';
        }
      }

      // Rellenar celdas vacías al final del mes
      while ((lastDay.getDay() + lastDay.getDate() - 1) % 7 !== 6) {
        calendarHTML += '<td class="m-2" style="background-color:#F0F5F2; width: 160px;" ></td>';
        lastDay.setDate(lastDay.getDate() + 1);
      }

      calendarHTML += '</tr></table>';
      calendarContainer.innerHTML = calendarHTML;
    }

    renderCalendar();

    prevButton.addEventListener('click', function() {
      if (currentMonth === 0) {
        currentMonth = 11;
        currentYear--;
      } else {
        currentMonth--;
      }
      renderCalendar();
    });

    nextButton.addEventListener('click', function() {
      if (currentMonth === 11) {
        currentMonth = 0;
        currentYear++;
      } else {
        currentMonth++;
      }
      renderCalendar();
    });


    const inicioEvento = document.querySelectorAll('.inicioEvento');
        for(let j=0; j<inicioEvento.length; j++){
            const valorInicio = inicioEvento[j].textContent;
            console.log(valorInicio);
        }

    const cardEvento = document.querySelectorAll('.card-evento');
        for(let i=0; i<cardEvento.length; i++){                
            cardEvento[i].classList.add('d-none');
        }

    const diaSemana = document.querySelectorAll('.dias-semanas');
        for(let d = 0; d<diaSemana.length; d++){
            const valorDiaSemana = diaSemana[d].textContent;
            console.log(valorDiaSemana);
        }

    const fecha = new Date();
    
    const dia = fecha.getDate();

    const mes = fecha.getMonth() + 1;

    const anio = fecha.getFullYear();
    console.log(dia+"/"+mes+"/"+anio);

</script>


<script>   
        document.addEventListener('DOMContentLoaded', ()=>{
            const meses = [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ];

            const calendarContainer = document.getElementById('calendario');
            const prevButton = document.getElementById('anterior');
            const nextButton = document.getElementById('siguiente');
            const currentDate = new Date();

            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();

            function renderCalendar() {
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            
            let calendarHTML = `<h2>${meses[currentMonth]} ${currentYear}</h2>`;
            calendarHTML += "<table class='w-100 shadow' style='margin-top:50px;margin-bottom:50px; border-radius:30px!important;'><tr style='height:50px;background-color:#D4F0D6;'>";

            // Encabezado: días de la semana
            const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            for (let day of daysOfWeek) {
                calendarHTML += `<th class="text-center text-primary">${day}</th>`;
            }
            calendarHTML += '</tr><tr>';

            // Rellenar celdas vacías al inicio del mes
            for (let i = 0; i < firstDay.getDay(); i++) {
                calendarHTML += '<td class="m-2" style="background-color:#F0F5F2; width: 160px!imporant;"></td>';
            }

            // Días del mes
            for (let day = 1; day <= lastDay.getDate(); day++) {
                calendarHTML += `<td class='text-center text-primary dias-semanas dia-${day}' style='height:160px;width: 160px!important; border:1px solid #D4EEDE;'>${day}</td>`;
                
                if ((firstDay.getDay() + day - 1) % 7 === 6) {
                calendarHTML += '</tr><tr>';
                }
            }

            // Rellenar celdas vacías al final del mes
            while ((lastDay.getDay() + lastDay.getDate() - 1) % 7 !== 6) {
                calendarHTML += '<td class="m-2" style="background-color:#F0F5F2; width: 160px!important;"></td>';
                lastDay.setDate(lastDay.getDate() + 1);
            }

            calendarHTML += '</tr></table>';
            calendarContainer.innerHTML = calendarHTML;
            }

            renderCalendar();

            prevButton.addEventListener('click', function(e) {
            e.preventDefault();    
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            renderCalendar();
            });

            nextButton.addEventListener('click', function(e) {
            e.preventDefault();
                if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            renderCalendar();
            });


            const inicioEvento = document.querySelectorAll('.inicioEvento');
                for(let j=0; j<inicioEvento.length; j++){
                    const valorInicio = inicioEvento[j].textContent;
                    console.log(valorInicio);
                }

            const cardEvento = document.querySelectorAll('.card-evento');
                for(let i=0; i<cardEvento.length; i++){                
                    cardEvento[i].classList.add('d-none');
                }

            const diaSemana = document.querySelectorAll('.dias-semanas');
                for(let d = 0; d<diaSemana.length; d++){
                    const valorDiaSemana = diaSemana[d].textContent;
                    console.log(valorDiaSemana);
                }

            const fecha = new Date();
            
            const dia = fecha.getDate();

            const mes = fecha.getMonth() + 1;

            const anio = fecha.getFullYear();
            console.log(dia+"/"+mes+"/"+anio);
        });
    </script>