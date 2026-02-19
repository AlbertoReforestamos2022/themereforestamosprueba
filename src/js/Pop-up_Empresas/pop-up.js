// document.addEventListener("DOMContentLoaded", ()=> {
//     // Bótones actividad 
//     const btns = document.querySelectorAll('.btn-actividad');
//     const contents = document.querySelectorAll('.contenido-actividad');
//     const primeraActividadContent = document.querySelectorAll('.reforestacion-content'); 

//     // contenido actividad
//     contents.forEach(content => {
//         content.classList.add('d-none'); 
        
//         if( content.classList.contains('reforestacion-content') ){
//             content.classList.remove('d-none'); 
//         }

//         // botones actividad uno
//         btns.forEach(btn => {
//             if(btn.classList.contains('actividad-uno')) {
//                 btn.classList.add('active', 'text-white');
//             }
//         })

//     })    
        
//     // Mostrar el contenido de la primera actividad
//     primeraActividadContent.forEach(actividad => {
//         if(actividad.classList.contains('d-none')){
//             actividad.classList.remove('d-none'); 
//         }
//     })


//     // contenido primer modal 
//     let cerrarContenido = document.querySelectorAll('#cerrarPopup');
//     cerrarContenido.forEach(botonCerrar => {
//         botonCerrar.addEventListener('click', ()=> {
//         let contenidoModal = document.querySelectorAll('.content-card-aliados'); 

//             contenidoModal.forEach(contenido => {
//                 contenido.preventDefault();
//                 const contentReforestacion = document.querySelectorAll('.reforestacion-content');
                
//                 contentReforestacion.forEach(content=> {
//                     if(content.classList.contains('d-none')) { 
//                         content.classList.remove('d-none');
//                     }
                    
//                     // Botón Reforestación
//                     const btnReforestacion = document.querySelectorAll('.actividad-uno');
//                     btnReforestacion.forEach(btn => {
//                         btn.classList.add('active', 'text-white');
//                     })
//                 })



//                 const contentMantenimiento = document.querySelectorAll('.mantenimiento-content');
//                 contentMantenimiento.forEach(content=> {
//                     if(!content.classList.contains('d-none')) { 
//                         content.classList.add('d-none');
//                     }
//                     // Contenido Botón
//                     const btnMantenimiento = document.querySelectorAll('.actividad-dos'); 
//                     btnMantenimiento.forEach(btn => {
//                         if(btn.classList.contains('active'))
//                         btn.classList.remove('active', 'text-white');
//                     })

//                 })

//                 const contentBrechas = document.querySelectorAll('.brechas-content');
//                 contentBrechas.forEach(content=> {
//                     if(!content.classList.contains('d-none')) { 
//                         content.classList.add('d-none');
//                     }
//                     // Contenido Botón
//                     const btnBrechas = document.querySelectorAll('.actividad-tres');
//                     btnBrechas.forEach(btn => {
//                         if(btn.classList.contains('active'))
//                         btn.classList.remove('active', 'text-white');
//                     })

//                 })

//                 /** Content otras actividades */
//                 const contentActividadesUno = document.querySelectorAll('.actividades-uno-content');
//                 contentActividadesUno.forEach(content=> {
//                     if(!content.classList.contains('d-none')) { 
//                         content.classList.add('d-none');
//                     }
//                     // Contenido Botón
//                     const btnOtrasActividadesUno = document.querySelectorAll('.actividad-cuatro');
//                     btnOtrasActividadesUno.forEach(btn => {
//                         if(btn.classList.contains('active'))
//                         btn.classList.remove('active', 'text-white');
//                     })

//                 })

//                 const contentActividadesDos = document.querySelectorAll('.actividades-dos-content');
//                 contentActividadesDos.forEach(content=> {
//                     if(!content.classList.contains('d-none')) { 
//                         content.classList.add('d-none');
//                     }
//                     // Contenido Botón
//                     const btnOtrasActividadesDos = document.querySelectorAll('.actividad-cinco');
//                     btnOtrasActividadesDos.forEach(btn => {
//                         if(btn.classList.contains('active'))
//                         btn.classList.remove('active', 'text-white');
//                     })

//                 })

//             })

//         })
//     })

//     // función const .btn-acitividad
//     btns.forEach(btn => {
//         btn.addEventListener("click", function() {
//             const id = btn.getAttribute("data-target");
//             const targetContent = document.getElementById(id);

//             if (targetContent) {
//                 btns.forEach(btn => btn.classList.remove('active', 'text-white'));
//                 contents.forEach(content => content.classList.add('d-none'));

//                 btn.classList.add('active', 'text-white');
//                 targetContent.classList.remove('d-none');
//             }


//         });
//     });

// });