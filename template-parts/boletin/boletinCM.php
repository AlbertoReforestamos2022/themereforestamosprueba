<?php
    function nombreError(){ ?>
        <script>
            function mensajeError(){
                const contentMensaje = document.querySelector('.nombre-error');
                contentMensaje.setAttribute('style', 'margin-top:10px; margin-bottom:10px;');
                const containerMensaje = document.createElement('div');
                containerMensaje.classList.add('container', 'content-mensaje-error');
                containerMensaje.innerHTML = `
                <div class="row row-cols-1 row-cols-md-2 justify-content-start mensaje-error">
                    <div class="col">
                        <h6 class="text-danger">El campo de nombre no puede ir vacio</h6>
                    </div>
                </div>
                `;

                // idioma 
                const idiomaSeleccionado = localStorage.getItem("idioma");
                if(idiomaSeleccionado === 'en-US'){
                    containerMensaje.innerHTML = `
                    <div class="row row-cols-1 row-cols-md-2 justify-content-start mensaje-error">
                        <div class="col">
                            <h6 class="text-danger">Name field cannot be empty</h6>
                        </div>
                    </div>
                    `;
                }

                contentMensaje.appendChild(containerMensaje);

                setTimeout( function() { 
                containerMensaje.remove();
                }, 3000 );
            }
            mensajeError();
        </script>
    <?php
    }

    function correoError() { ?>
        <script>
            function mensajeError(){
                const contentMensaje = document.querySelector('.correo-error');
                contentMensaje.setAttribute('style', 'margin-top:10px; margin-bottom:10px;');
                const containerMensaje = document.createElement('div');
                containerMensaje.classList.add('container', 'content-mensaje-error');
                // containerMensaje.setAttribute('style', 'margin-top:30px;')
                containerMensaje.innerHTML = `
                <div class="row row-cols-1 row-cols-md-2 justify-content-start mensaje-error">
                    <div class="col">
                        <h6 class="text-danger">El campo de email no puede ir vacio</h6>
                    </div>
                </div>
                `;

                // idioma 
                const idiomaSeleccionado = localStorage.getItem("idioma");
                if(idiomaSeleccionado === 'en-US'){
                    containerMensaje.innerHTML = `
                    <div class="row row-cols-1 row-cols-md-2 justify-content-start mensaje-error">
                        <div class="col">
                            <h6 class="text-danger">E-mail field cannot be empty</h6>
                        </div>
                    </div>
                    `;
                }

                contentMensaje.appendChild(containerMensaje);

                setTimeout( function() { 
                //window.location.href = "http://localhost:8080/wordpress/boletin/"; 
                containerMensaje.remove();

                }, 3000 );
            }
            mensajeError();
        </script>
    <?php
    }

    function mensajeEnviar() { ?>
        <script>
            function mensajeEnviar(){
                const contentMensaje = document.querySelector('#mensaje-boletin');
                const containerMensaje = document.createElement('div');
                containerMensaje.classList.add('container', 'content-mensaje-enviar');
                containerMensaje.setAttribute('style', 'margin-top:30px;');

                containerMensaje.innerHTML = `
                <div class="row row-cols-1 row-cols-md-2 justify-content-center mensaje-enviar">
                    <div class="col">
                        <h3 class="bg-light text-white p-2 rounded-2 text-center">¡Gracias por suscribirte a nuestro boletín!</h3>
                    </div>
                </div>
                `;

                // idioma 
                const idiomaSeleccionado = localStorage.getItem("idioma");
                if(idiomaSeleccionado === 'en-US'){
                    containerMensaje.innerHTML = `
                    <div class="row row-cols-1 row-cols-md-2 justify-content-center mensaje-enviar">
                        <div class="col">
                            <h3 class="bg-light text-white p-2 rounded-2 text-center">Thank you for subscribing to our newsletter!</h3>
                        </div>
                    </div>
                    `;
                }

                contentMensaje.appendChild(containerMensaje);

                setTimeout( function() { 
                containerMensaje.remove();
                window.location.href = "http://localhost:8080/wordpress/";
                }, 2000 );
            }

            mensajeEnviar();
        </script>        
    <?php
    }

    function contenidoEn() { ?>
        <script>
            function contenidoEn(){
                const idiomaSeleccionado = localStorage.getItem("idioma");
                const valueNombre = document.querySelector('#nombreBoletín'); 
                const valueCorreo = document.querySelector('#correoBoletín'); 
                const botonSuscribir = document.querySelector('#sus'); 
                
                if(idiomaSeleccionado === 'en-US') {
                    valueNombre.placeholder = 'Name';
                    valueCorreo.placeholder = 'E-Mail';
                    botonSuscribir.textContent = 'Subscribe me'; 
                }
            }

            contenidoEn();
        </script>        
        <?php
        }
    ?>
