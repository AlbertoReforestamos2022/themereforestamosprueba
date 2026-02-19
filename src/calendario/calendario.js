  
document.addEventListener('DOMContentLoaded', ()=>{
    const meses = [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ];

    // Contenedor calendario
    const calendarContainer = document.getElementById('calendario');
    // Botones anterior - siguiente para cambiar de Mes
    const prevButton = document.getElementById('anterior');
    const nextButton = document.getElementById('siguiente');
    
    // Declaración de lafecha
    const currentDate = new Date();
    // Declaración del Día
    let currentDay = currentDate.getDate();
    console.log('dia calendario: '+ currentDay);
    // Declaración del Mes 
    let currentMonth = currentDate.getMonth();
    console.log('Mes calendario: ' + currentMonth);
    // Declaración Año 
    let currentYear = currentDate.getFullYear();
    console.log('Año calendario: ' + currentYear);



    // Función para renderizar el calendario
    function renderCalendar() {
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    
    // Mes actual 
    let tituloCalendarHTML = document.querySelector('.mes-actual');
    tituloCalendarHTML.innerHTML = `${meses[currentMonth]} ${currentYear}`;
    // console.log(tituloCalendarHTML);
    // calendario
    let calendarHTML = "<table class='w-100 shadow' style='margin-top:50px;margin-bottom:50px; border-radius:30px!important;'><tr style='height:50px;background-color:#D4F0D6;'>";

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
    //<?php echo esc_url( home_url('/') ); ?>
    // Días del mes
    for (let day = 1; day <= lastDay.getDate(); day++) {       
        if(currentDay == day && tituloCalendarHTML.textContent == meses[currentMonth] && currentYear == anioTituloEvento.textContent ) {
            calendarHTML += `<td class='text-center text-white bg-light dias-semanas dia-${day}' style='height:160px;width: 160px!important; border:1px solid #D4EEDE;'>${day}</td>`;
        } else {
            calendarHTML += `<td class='text-center text-primary dias-semanas dia-${day}' style='height:160px;width: 160px!important; border:1px solid #D4EEDE;'>${day}</td>`;            
        }
        
        if ((firstDay.getDay() + day - 1) % 7 === 6) {
        calendarHTML += '</tr><tr>';
        }

    }

    // Rellenar celdas vacías al final del mes
    while ((lastDay.getDay()) % 7 !== 6) {
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

});