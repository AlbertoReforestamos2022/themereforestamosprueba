// Centrar imágenes y ajustar tamaño  
        document.addEventListener('DOMContentLoaded', ()=>{
            // Contenedor de la nota de blog 
            const contentParentImage = document.querySelector('.content-title'); 
            // Imagénes de la nota
            const imgsNotas = document.querySelectorAll('.content-title img');
            
            // Iterar sobre las imagenes que están dentro del contenedor de la nota del blog
            imgsNotas.forEach(img => {
                const parentImage = img.parentElement;
                
                // Agregar la clase 'img-fluid' a las imágenes de la nota
                if(!img.classList.contains('img-fluid')){
                    img.classList.add('img-fluid');
                }

                // Crear contenedor padre de la imagnen
                const contentImage = document.createElement('div');
                contentImage.classList.add('d-flex'); 
                contentImage.appendChild(img); 

                // Reemplazar el figure con el nuevo div
                if(parentImage) {
                    parentImage.insertBefore(contentImage, img.nextSibling);
                } else {
                    // Si la imagen no tiene un padre, insertar el nuevo div al final del body
                    contentParentImage.appendChild(contentImage);
                }

                // Agregar alineación del contenedor padre dependiendo del la alineación de la imagen. 
                const ALIGNS_IMAGES_TYPES = {
                    CENTER : ['aligncenter', 'justify-content-center'],
                    LEFT : ['alignleft', 'justify-content-start'],
                    RIGHT: ['alignright', 'justify-content-end']
                }

                Object.entries(ALIGNS_IMAGES_TYPES).forEach(([tipo, clases])=> {
                    if(img.classList.contains(clases[0])){
                        contentImage.classList.add(clases[1])
                    }
                })

            });
        })

// Centrar imágenes y ajustar tamaño  