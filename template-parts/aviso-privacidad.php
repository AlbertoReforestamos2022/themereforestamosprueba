    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-lg-10 ">
                <div class="card border-0 shadow con-a-p">
                    <div class="card-header bg-transparent border-0 text-center">
                    <h1 class="text-primary mt-4 aviso-title"> <?php echo get_the_title(); ?> </h1>
                    </div>
                    <div class="card-body p-3 p-md-5 text-black-50 content-title">
                        <?php echo the_content(); ?>      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido en inglés -->
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");
        const titulo = document.querySelector('.title-general');
        const contentAvisoPrivacidad = document.querySelector('.con-a-p');

            if(idiomaSeleccionado === 'en-US') {
                <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
                titulo.textContent = `<?php echo $tituloPrincipal ?>`;                
                // content_aviso_en
                contentAvisoPrivacidad.innerHTML = `
                <div class="card-header bg-transparent border-0 text-center">
                    <?php $titulo = get_post_meta( get_the_ID(), 'title_aviso_en', true); ?>
                    <?php $contenido = get_post_meta( get_the_ID(), 'content_aviso_en', true); ?> 

                    <h1 class="text-primary mt-4 aviso-title"> <?php echo $titulo; ?> </h1>
                    </div>
                    <div class="card-body p-3 p-md-5 text-black-50 content-title">
                        <?php echo apply_filters('the_content', $contenido); ?>      
                </div>
                `;
            }
        })
    </script>
    <!-- Contenido en inglés -->