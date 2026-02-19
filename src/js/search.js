(function( $ ) {21
	'use strict';

    const select_table = document.querySelector('.notas-col');
    const search_input = `  <section class="container">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6 d-flex justify-content-center">
                                        <input class="p-1 w-100 rounded-2" type="search" id="table-search" name="table-search" placeholder="Buscar notas" />
                                    </div>
                                </div>
                            </section>`;
    // insertar input
    $(search_input).insertBefore(select_table);

    //Agrgar estilos al buscador
    const styleSearch = document.querySelector('#table-search');
    styleSearch.setAttribute('style', 'outline: none;border: 2px solid #ccc!important;');


    // Búsqueda
    let rows = $(select_table).find('.col-nota');
    console.log(rows);

    $('#table-search').keyup(function() {
        const search_value = $.trim($(this).val()).replace(/ +/g,' ').toLowerCase();

        rows.show().filter( function(){
            const row_value = $(this).text().toLowerCase();
            return !~row_value.indexOf(search_value);
        }).hide();

    });




})( jQuery );
