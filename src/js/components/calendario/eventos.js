const date = new Date();

const renderCalendar = (e) => {
  e.preventDefault();
  date.setDate(1);
  
  // Días del mes
  const monthDays = document.querySelector(".dias");
  // Meses del año
  const month = document.querySelector(".meses");
 
  // Último día 
  const lastDay = new Date(
    date.getFullYear(),
    date.getMonth() + 1,
    0
  ).getDate();

  const prevLastDay = new Date(
    date.getFullYear(),
    date.getMonth(),
    0
  ).getDate();

// Primer día del Mes Index 
  const firstDayIndex = date.getDay();

// Último día del Mes Index 
  const lastDayIndex = new Date(
    date.getFullYear(),
    date.getMonth() + 1,
    0
  ).getDay();

  const nextDays = 7 - lastDayIndex - 1;

  const meses = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre"
  ];

  document.querySelector(".meses h2").innerHTML = meses[date.getMonth()];
  document.querySelector(".meses p").innerHTML = date.getFullYear();
  month.classList.add("verde-rm");

  let days = "";

  for (let x = firstDayIndex; x > 0; x--) {
    days += `<p class="cal-btn prev-date">${prevLastDay - x + 1}</p>`;
  }

  for (let i = 1; i <= lastDay; i++) {
    if (
      i === new Date().getDate() &&
      date.getMonth() === new Date().getMonth()
    ) {
      days += `<p class="cal-btn today">${i}</p>`;
    } else {
      days += `<p class="cal-btn dias-mes-${i}">${i}</p>`;
    } 

  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<p class="cal-btn next-date">${j}</p>`;
    monthDays.innerHTML = days;
  }

};

document.querySelector(".anterior").addEventListener("click", () => {
  date.setMonth(date.getMonth() - 1);
  renderCalendar();
});

document.querySelector(".siguiente").addEventListener("click", () => {
  date.setMonth(date.getMonth() + 1);
  renderCalendar();
});

renderCalendar();



/** ----------------------------------------------------------------------------------------------------------------------------------------------   */
// Dias Eventos 
const dia1 = document.querySelector('.dias-mes-1');
const dia2 = document.querySelector('.dias-mes-2');
const dia3 = document.querySelector('.dias-mes-3');
const dia4 = document.querySelector('.dias-mes-4');
const dia5 = document.querySelector('.dias-mes-5');
const dia6 = document.querySelector('.dias-mes-6');

dia1.classList.add('text-bg-primary', 'text-white', 'rounded-5');
dia2.classList.add('text-bg-primary', 'text-white', 'rounded-5');
dia3.classList.add('text-bg-primary', 'text-white', 'rounded-5');
dia4.classList.add('text-bg-primary', 'text-white', 'rounded-5');
dia5.classList.add('text-bg-primary', 'text-white', 'rounded-5');
dia6.classList.add('text-bg-primary', 'text-white', 'rounded-5');


/** Modales */



