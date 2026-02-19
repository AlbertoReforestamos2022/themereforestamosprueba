
<!-- Formulario boletín -->
    <div class="container" style="padding-top:100px; padding-bottom:30px;">
        <div class="row row-cols-1 row-cols-md-2 justify-content-center">
            <div class="col d-flex justify-content-center">
                <form id="boletin-form" class="row align-items-center w-100 gap-2" method="POST">
                    <div class="col-12">
                        <input type="text" name="nombreBoletin" class="form-control" id="nombreBoletín" placeholder="Nombre completo" require>
                        <div class="nombre-error"></div>
                    </div>
                    <div class="col-12">
                        <input type="email" name="correoBoletin" class="form-control" id="correoBoletín" placeholder="Correo" require>
                        <div class="correo-error"></div>
                    </div>
                    <div class="col-6">
                        <button name="register" type="submit" class="btn btn-outline-light" id="sus">Suscribirme</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="mensaje-boletin"></div>
        
    </div>
    <?php include("boletin/boletinConf.php"); ?>
<!-- ./Formulario boletín -->


<!-- Contenido en inglés -->
<script>
    document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");
        const valueNombre = document.querySelector('#nombreBoletín'); 
        const valueCorreo = document.querySelector('#correoBoletín'); 
        const botonSuscribir = document.querySelector('#sus'); 
        
        if(idiomaSeleccionado === 'en-US') {
            valueNombre.placeholder = 'Name';
            valueCorreo.placeholder = 'E-Mail';
            botonSuscribir.textContent = 'Subscribe me'; 
        }
    })

</script>
<!-- Contenido en inglés -->


