<main class="container not-en">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 col-lg-10">
            <div class="card border-0 shadow-sm con-no-bl">
                <?php $titulo = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', true); ?>
                <?php $contenido = get_post_meta( get_the_ID(), 'nota_blog_ingles_contenido_nota_ingles', true); ?>
                <?php $contenidoSinProcesar = do_shortcode($contenido); ?>
                <?php $contenidoShortCode = do_shortcode(apply_filters('the_content', $contenido)) ?> 

                <div class="card-header bg-transparent border-0 p-4">
                    <div class="p-3">
                        <h3 class="text-primary text-center tit-nota-en"> <?php echo $titulo;?> </h3>
                    </div>

                    <div class="fw-bold">
                        <h4 class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>

                        <hr class="p-2 text-primary">
                    </div>
                </div>

                <div class="card-body px-3 px-md-5 fs-5">
                    <div class="text-justify cont-nota cont-not-en content-title">
                        <?php echo $contenidoShortCode; ?> 
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="fw-bold">
                        <hr class="p-2 text-primary">
                        <h4 class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<main class="container not-es">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 col-lg-10">
            <div class="card border-0 shadow-sm con-no-bl">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="p-3">
                        <h2 class="text-primary text-center tit-nota-es"> <?php echo the_title();?> </h2>
                    </div>

                    <div class="fw-bold">
                        <h4 class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Comparte esta nota en tus Redes Sociales</p>

                        <hr class="p-2 text-primary">
                    </div>
                </div>
                <div class="card-body px-3 px-md-5 fs-5">
                    <div class="text-justify cont-nota-es content-title">
                        <?php echo do_shortcode(the_content()); ?>    
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="fw-bold">
                        <hr class="p-2 text-primary">
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Comparte esta nota en tus Redes Sociales</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>


<!-- Contenido en inglés -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const idiomaSeleccionado = localStorage.getItem("idioma"); // idioma Seleccionado
        const contenidoNotaBlog = document.querySelector('.con-no-bl'); // Contenido nota blog
        const notaEn = document.querySelector('.not-en'); // Nota en inglés        
        const notaEs = document.querySelector('.not-es'); // Nota en Español        
        const contenidoNota = document.querySelector('.cont-not-en'); // Contenido nota en inglés

        notaEn.classList.add('d-none'); // Ocultar la nota en inglés por defecto

        if (idiomaSeleccionado === 'en-US') {
            notaEn.classList.remove('d-none'); // Mostrar la nota en inglés
            notaEs.classList.add('d-none'); // Ocultar la nota en español


            // Fecha Notas 
            let fechaNotas = document.querySelectorAll('.dat-not');
            let fechaOBJ = {}

            function traducirMeses(fecha) {
                // Mapa de nombres de meses en español a inglés
                const mesesES = ['enero,', 'febrero,', 'marzo,', 'abril,', 'mayo,', 'junio,', 'julio,', 'agosto,', 'septiembre,', 'octubre,', 'noviembre,', 'diciembre,'];
                const mesesEN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                // Separar la cadena de fecha en día, mes y año
                const partesFecha = fecha.split(' ');
                const dia = partesFecha[0];
                const mes = partesFecha[1];
                const anio = partesFecha[2];

                // Traducir el nombre del mes si está en español
                const indice = mesesES.findIndex((mesEspanol) => mesEspanol.toLowerCase() === mes.toLowerCase());
                const mesTraducido = indice !== -1 ? mesesEN[indice] : mes;

                // Formar la nueva cadena de fecha con el nombre del mes traducido en la posición deseada
                const nuevaFecha = mesTraducido + ' ' + dia + ', ' + anio;

                return nuevaFecha;
            }       

            fechaNotas.forEach((fecha) => {
                let fechaEnEspanol = fecha.textContent;
                let fechaTraducida = traducirMeses(fechaEnEspanol);

                fecha.textContent = fechaTraducida;      
            });            

            console.log('La nota está en inglés');
        }else if (idiomaSeleccionado !== 'en-US') {
            notaEn.classList.add('d-none'); // Ocultar la nota en inglés
        }
    });

</script>
<!-- Contenido en inglés -->

<li><span class="list-text-date">Fecha limite para postularse: </span> <?php echo $fecha; ?> </li>