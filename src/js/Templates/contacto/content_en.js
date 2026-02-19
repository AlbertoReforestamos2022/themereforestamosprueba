
    document.addEventListener('DOMContentLoaded', ()=> {
        // contenido localStorage
        const idiomaSeleccionado = localStorage.getItem("idioma");

        // Titulos principales
        const TITULOS_POR_IDIOMA = {
            'es' : '.title-general',
            'en-US' : '.title-principal-en',
        }

        // Contenido Secciones
        const CONTENIDO_SECCIONES = {
            'es' : {
                FORMULARIO : {
                    NOMBRE : {
                        CLASE : '.tit-con-nom', 
                        TEXTO : document.querySelector('.tit-con-nom').textContent,
                    }, 
                    CORREO: {
                        CLASE : '.tit-con-corr',
                        TEXTO : document.querySelector('.tit-con-corr').textContent,
                    },
                    ASUNTO: {
                        CLASE : '.tit-con-asu',
                        TEXTO : document.querySelector('.tit-con-asu').textContent,
                    },
                    MENSAJE: {
                        CLASE : '.tit-con-men',
                        TEXTO : document.querySelector('.tit-con-men').textContent,
                    },
                    BOTON_ENVIAR: {
                        CLASE : '#tit-con-enviar',
                        TEXTO : document.querySelector('#tit-con-enviar').value,
                    }
                },  
                UBICACIONES : {
                    CLASE : '.titulo-oficina',
                    TEXTO : {
                        CDMX : document.querySelectorAll('.titulo-oficina')[0].textContent,
                        GDL  : document.querySelectorAll('.titulo-oficina')[1].textContent, 
                    }
                },
            } ,
            'en-US' : {
                FORMULARIO : {
                    NOMBRE : {
                        CLASE : '.tit-con-nom', 
                        TEXTO : 'Name',
                    }, 
                    CORREO: {
                        CLASE : '.tit-con-corr',
                        TEXTO : 'E-mail',
                    },
                    ASUNTO: {
                        CLASE : '.tit-con-asu',
                        TEXTO : 'Subject',
                    },
                    MENSAJE: {
                        CLASE : '.tit-con-men',
                        TEXTO : 'Message',

                    },
                    BOTON_ENVIAR: {
                        CLASE : '#tit-con-enviar',
                        TEXTO : 'Send',
                    }   
                },

                UBICACIONES : {
                    CLASE : '.titulo-ubicaciones-en',
                    TEXTO : {
                        CDMX : document.querySelectorAll('.titulo-ubicaciones-en')[0].textContent,
                        GDL  : document.querySelectorAll('.titulo-ubicaciones-en')[1].textContent, 
                    }
                },
            }
        }

        // Contenido titulo principal dependiendo del idioma
        const selectorTitulos = TITULOS_POR_IDIOMA[idiomaSeleccionado]; 
        const titulo = document.querySelector(selectorTitulos).textContent
        // asignar el texto del titulo dependiendo el Idioma
        document.querySelector('.title-general').textContent = titulo; 

        // Elementos por idioma
        const ELEMENTOS_POR_IDIOMA = {
            'es': {
                FORMULARIO: CONTENIDO_SECCIONES['es'].FORMULARIO,
                UBICACIONES: CONTENIDO_SECCIONES['en-US'].UBICACIONES,
            },
            'en-US': {
                FORMULARIO: CONTENIDO_SECCIONES['en-US'].FORMULARIO,
                UBICACIONES: CONTENIDO_SECCIONES['es'].UBICACIONES,
            }
        };

        const elementos = ELEMENTOS_POR_IDIOMA[idiomaSeleccionado];

        // Formulario
        Object.values(elementos.FORMULARIO).forEach((elemento) => {  let contenido = document.querySelector(elemento.CLASE);  contenido.textContent = elemento.TEXTO;  if(contenido.tagName == 'INPUT'){ contenido.value = elemento.TEXTO }; })
        
        // Ubicaciones oficinas
        if(idiomaSeleccionado == 'en-US'){
            let ubicaciones = document.querySelectorAll(CONTENIDO_SECCIONES['es'].UBICACIONES.CLASE)
            let ubicaciones_en = document.querySelectorAll(CONTENIDO_SECCIONES["en-US"].UBICACIONES.CLASE) ; 

            ubicaciones.forEach( ubicacion => {
                console.log(ubicacion); 

                ubicaciones_en.forEach( ubicacion_en => {
                    ubicacion.textContent = ubicacion_en.textContent; 
                })
            })
        }


    })

