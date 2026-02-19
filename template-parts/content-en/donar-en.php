    <div class="container py-5 donate_content_title_en" style="margin-top:90px;">
        <h1 class="h3 text-center text-primary fw-bold donate_subtitle-en">Thank you for supporting Reforestamos Mexico A.C.'s causes and projects.</h1>
        <p class="text-center text-black-50 donate_text-en">The following are the ways in which you can make your donation:</p>
    </div>           
          
    <section class="p-md-5 donate_section-en" style="background-color:#FAFBFE;">
        <div class="container">
            <div class="row d-flex justify-content-center my-5 ">
                <div class="col-lg-4">
                    <div class="shadow rounded-3 p-5 " style="height:300px; background-color:rgba(101, 180, 146,.75);">
                        <h4 class="text-center text-white fw-bold">You can donate with your credit or debit card.</h4>

                        <div class="d-flex justify-content-center" style="margin-bottom:40px;">
                            <?php  $donar_tarjeta = get_post_meta( get_the_ID(), 'img_tarjeta_en', true ); ?>
        
                            <div class="donate-card p-3">
                                <a href="https://donativos-reforestamosmexico.org/" target="_blank" class="text-decoration-none">
                                <img src="<?php echo $donar_tarjeta ?>" class="img-fluid" width="200" alt="donate with your credit or debit card">
                                </a>
                            </div>
                        </div>   
                    </div>
                </div>

                <div class="col-lg-4 mt-lg-0 mt-4">
                    <div class="shadow rounded-3 p-5" style="height:300px; background-color:rgba(236, 67, 44,.82)!important;">
                        <h4 class="text-center text-white fw-bold" sytle="">You can donate to our PayPal account</h4>
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