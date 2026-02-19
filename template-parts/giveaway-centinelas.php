<div class="container espacio-general">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow">
                <div class="card-header bg-transparent border-0 text-center">
                <h1 class="text-primary mt-4"> <?php echo get_the_title(); ?> </h1>
                </div>
                <div class="card-body p-3 p-md-5 text-black-50">
                    <?php echo the_content(); ?>      
                </div>
            </div>
        </div>
    </div>
</div>