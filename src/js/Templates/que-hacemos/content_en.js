console.log('Script que hacemos')

document.addEventListener('DOMContentLoaded', ()=> {
    const idiomaSeleccionado = localStorage.getItem("idioma");

    // Titulos por idioma
    const TITULOS_POR_IDIOMA = {
        'es' : '.title-general',
        'en-US' : '.title-principal-en'        
    }

    // Contenido secciones
    const CONTENIDO_SECCIONES = {
        'es' : {
            MODELOS_DE_PAISAJES : '.mo-ma-pa',
            POLITICAS_PUBLICAS: '.in-po-pu', 
            COMUNIDADES_DE_EMPRENDIMIENTO: '.com-em', 
            SECTOR_PRIVADO: '.se-pri',
            EMPODERAMIENTO_CIUDADANO : '.em-ciu',
            BOTONES : {
                SALIR: {
                    CLASS : '.b-s'
                },
                SABER_MAS: {
                    CLASS : '.b-s-m'
                }
            }
        }, 
        'en-US' : {
            MODELOS_DE_PAISAJES : '.modelo-paisajes-en',
            POLITICAS_PUBLICAS: '.inicidencia-politicas-publicas-en', 
            COMUNIDADES_DE_EMPRENDIMIENTO: '.comunidades-emprendimiento-en', 
            SECTOR_PRIVADO: '.sector-privado-en',
            EMPODERAMIENTO_CIUDADANO : '.empoderamiento-ciudadano-en',
            BOTONES : {
                SALIR: {
                    TEXTO : 'Exit'
                },
                SABER_MAS:  {
                    TEXTO : 'Learn more'
                }
            }
        }
    }

    // Contenido en español
    const modelosPaisajes = document.querySelector(CONTENIDO_SECCIONES['es'].MODELOS_DE_PAISAJES);
    const politicasPublicas = document.querySelector(CONTENIDO_SECCIONES['es'].POLITICAS_PUBLICAS);
    const cominidadesEmprendimiento = document.querySelector(CONTENIDO_SECCIONES['es'].COMUNIDADES_DE_EMPRENDIMIENTO);
    const sectorPrivado = document.querySelector(CONTENIDO_SECCIONES['es'].SECTOR_PRIVADO);
    const empoderamientoCiudadano = document.querySelector(CONTENIDO_SECCIONES['es'].EMPODERAMIENTO_CIUDADANO); 

    // Contenido en inglés
    const modelosPaisajes_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].MODELOS_DE_PAISAJES)
    const politicasPublicas_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].POLITICAS_PUBLICAS)
    const cominidadesEmprendimiento_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].COMUNIDADES_DE_EMPRENDIMIENTO)
    const sectorPrivado_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].SECTOR_PRIVADO)
    const empoderamientoCiudadano_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].EMPODERAMIENTO_CIUDADANO)

    // Contenido titulo principal dependiendo del idioma
    const selectorTitulos = TITULOS_POR_IDIOMA[idiomaSeleccionado]; 
    const titulo = document.querySelector(selectorTitulos).textContent
    // asignar el texto del titulo dependiendo el Idioma
    document.querySelector('.title-general').textContent = titulo; 

    // Elementos por idioma
    const ELEMENTOS_POR_IDIOMA = {
        'en-US' : {
            modelosPaisajes: modelosPaisajes,
            politicasPublicas: politicasPublicas,
            cominidadesEmprendimiento: cominidadesEmprendimiento,
            sectorPrivado: sectorPrivado,
            empoderamientoCiudadano: empoderamientoCiudadano
        },
        'es' : {
            modelosPaisajes: modelosPaisajes_EN,
            politicasPublicas: politicasPublicas_EN,
            cominidadesEmprendimiento: cominidadesEmprendimiento_EN,
            sectorPrivado: sectorPrivado_EN,
            empoderamientoCiudadano: empoderamientoCiudadano_EN
        }
    }

    // Mostramos los elemntos dependiendo el Idioma
    const elementos = ELEMENTOS_POR_IDIOMA[idiomaSeleccionado]; 
    Object.values(elementos).forEach(elemento => {  elemento.innerHTML = '';  })

    const TEXTO_BOTONES_POR_IDIOMA = {
        'es': {
            SALIR: 'Salir',
            SABER_MAS: 'Saber más'
        }, 
        'en-US': {
            SALIR : 'Exit',
            SABER_MAS: 'Learn more'
        },

    };

    // Obtener los textos de los botones para el idioma seleccionado
    const textosBotones = TEXTO_BOTONES_POR_IDIOMA[idiomaSeleccionado];

    // Botones Modal
    let botonSalir = document.querySelectorAll(CONTENIDO_SECCIONES['es'].BOTONES.SALIR.CLASS);
    let botonSaberMas = document.querySelectorAll(CONTENIDO_SECCIONES['es'].BOTONES.SABER_MAS.CLASS);

    // Asignar texto a los botones
    botonSalir.forEach((salir) => {
        salir.textContent = textosBotones.SALIR;
    });

    botonSaberMas.forEach((saber) => {
        saber.textContent = textosBotones.SABER_MAS;
    });

  })