<div class="container py-5" style="margin-top:140px;">

    <h1 class="h3 text-center text-primary fw-bold su-ti-1">Gracias por apoyar las causas y proyectos de Reforestamos México A.C. </h1>

    <p class="text-center text-black-50 tex-do">A continuación te presentamos las formas en las que puedes realizar tu donativo:</p>

</div>



<section class="p-md-5 con-sec-don" style="background-color:#FAFBFE;">
    <div class="container">
        <div class="row d-flex justify-content-center my-5 ">
            <div class="col-lg-4 mt-lg-0 mt-4">
                <div class="shadow rounded-3 p-5" style="height:300px; background-color:rgba(236, 67, 44,.82)!important;">
                    <h4 class="text-center text-white fw-bold" sytle="">Donar a nuestra cuenta PayPal</h4>
                    <?php  $donar_paypal = get_post_meta( get_the_ID(), 'img_paypal', true ); ?>
                    <div class="d-flex justify-content-center" style="margin-top:40px; margin-bottom:40px;">
                        <div class="form-paypal p-3">
                            <form action="https://www.paypal.com/donate" method="post" target="_blank">
                                <input type="hidden" name="hosted_button_id" value="DE3ZNNJW8RKK2" />
                                <input type="image" src="<?php echo $donar_paypal ?>" border="0" width="160" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donar con el botón PayPal" />
                                <img alt="" border="0" src="https://www.paypal.com/es_MX/i/scr/pixel.gif" width="0" height="0" />
                            </form>
                        </div>
                    </div> 
                </div> 
            </div>

            <div class="col-lg-4 mt-lg-0 mt-4">
                <div class="shadow rounded-3 p-5" style="height:300px; background-color:rgba(55, 182, 99, 0.85)!important;">
                    <h4 class="text-center text-white fw-bold" sytle="">Donar con tarjeta de Crédito o Débito</h4>
                    <?php  $donar_tarjeta = get_post_meta( get_the_ID(), 'img_tarjeta', true ); ?>
                    <?php $code_btn_donar = get_post_meta( get_the_ID(), 'code_contenido_donar', true ); ?>
                    <div class="d-flex justify-content-center" style="margin-top:40px; margin-bottom:40px;">
                        <div class="form-tarjeta-credito p-3">
                            
                            <?php 
                                if(!empty($code_btn_donar)) {
                                    echo $code_btn_donar;        
                                } else {
                                echo `
                                <script async src="https://js.stripe.com/v3/buy-button.js"></script>
                                
                                <stripe-buy-button
                                buy-button-id="buy_btn_1RigVTKtpB5zD6OUfryBiOie"
                                publishable-key="pk_live_51RTbEaKtpB5zD6OUxu5WgEcpiJbToocvfJk2Bj1akznRQV5t6Hu9UJLfvmcH4mkffhNIQD6051SifOH7b372DKsE00n45qIpXM"
                                >
                                </stripe-buy-button>
                                `;

                                }
                             ?>

 

                            </a>
                        </div>
                    </div> 
                </div> 
            </div>

            
        </div>


        <div class="row">
            
        </div>

    </div>

</section>




<script>

    document.addEventListener('DOMContentLoaded', ()=> {

        const idiomaSeleccionado = localStorage.getItem("idioma");

        const subtituloDonar     = document.querySelector('.su-ti-1');

        const textoDonar         = document.querySelector('.tex-do');

        const contenidoDonar     = document.querySelector('.con-sec-don');



        if(idiomaSeleccionado === 'en-US'){

            subtituloDonar.textContent = `Thank you for supporting Reforestamos Mexico’s causes and projects.`;



            textoDonar.textContent = `The following are the ways in which you can make your donation: `;



            contenidoDonar.innerHTML = `

            <section class="p-md-5 con-sec-don" style="background-color:#FAFBFE;">

                <div class="container">

                    <div class="row d-flex justify-content-center my-5 ">

                        <div class="col-lg-4 mt-lg-0 mt-4">

                            <div class="shadow rounded-3 p-5" style="height:300px; background-color:rgba(236, 67, 44,.82)!important;">

                                <h4 class="text-center text-white fw-bold" sytle="">Make your gift with our PayPal account</h4>

                                <?php  $donar_paypal = get_post_meta( get_the_ID(), 'img_paypal_en', true ); ?>

                                <div class="d-flex justify-content-center" style="margin-top:40px; margin-bottom:40px;">

                                    <div class="form-paypal p-3">

                                        <form action="https://www.paypal.com/donate" method="post" target="_blank">

                                            <input type="hidden" name="hosted_button_id" value="DE3ZNNJW8RKK2" />

                                            <input type="image" src="<?php echo $donar_paypal ?>" border="0" width="160" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donar con el botón PayPal" />

                                            <img alt="" border="0" src="https://www.paypal.com/es_MX/i/scr/pixel.gif" width="0" height="0" />

                                        </form>

                                    </div>

                                </div> 

                            </div> 

                        </div>

                    </div>



                </div>

            </section>            

            `;

        }

    })

</script>



