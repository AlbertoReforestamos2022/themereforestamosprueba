
    <form class="input-group" role="search" method="get"  action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="text" class="form-control border-light search-field" placeholder="Buscar por palabra clave" value="<?php echo get_search_query(); ?>" name="s">
        <button class="btn btn-outline-light search-submit" type="submit" id="button-search">Buscar</button>
    </form>