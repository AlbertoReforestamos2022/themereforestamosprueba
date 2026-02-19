


<section class="search-form-custom">
    <form role="search" method="get" class="search-form-custom" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label>
            <span class="screen-reader-text">Buscar:</span>
            <input type="search" class="search-field-custom" placeholder="Buscar..." value="<?php echo get_search_query(); ?>" name="s-custom" />
        </label>
        <input type="submit" class="search-submit-custom" value="Buscar" />
    </form>
</section>