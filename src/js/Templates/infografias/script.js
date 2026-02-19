document.addEventListener('DOMContentLoaded', () => {

    const rowCategories = document.querySelector('.row_categories_infographics');
    const rowInfographics = document.querySelector('.row_infographics');
    const containerResultados = document.querySelector('.row_results');
    const titleResultados = document.getElementById('title_results');

    const inputKeyword = document.getElementById('keywordFilter');
    const inputCategory = document.getElementById('categoryFilter');
    const btnSearch = document.getElementById('searchButton');
    const btnReset = document.getElementById('resetFilters');

    let todasLasInfografias = [];

    const btnVolverCategorias = document.querySelectorAll('#btnVolverCategorias');
    const btnVolverCategoriasContainer = document.querySelectorAll('#btnVolverCategoriasContainer');

    function borrarbtnVerMas() {
        const categoryGroup = document.querySelectorAll('.category-group');
        categoryGroup.forEach(group => {
            const btnVerMas = group.querySelectorAll('.toggle-btn');

            btnVerMas.forEach(btn => {
                btn.classList.add('d-none'); 
            })
        })
    }

    function configurarBotonesVerMas() {
        const categoryGroup = document.querySelectorAll('.category-group');

        categoryGroup.forEach(grupo => {
            const infografias = grupo.querySelectorAll('.col_infographics');
            const btnVerMas = grupo.querySelectorAll('.toggle-btn');

            if (!btnVerMas) return; // Si no hay botón, salta este grupo

            const cantidadInicial = 5;
            let cantidadMostrada = cantidadInicial;
            let mostrandoTodo = false; 

            function mostrarInfografias(limit) {
                infografias.forEach((inf, index) => {
                    inf.classList.toggle('d-none', index >= limit);
                });
            }

            // Configuración inicial
            mostrarInfografias(cantidadInicial);
            btnVerMas.forEach(btn => {
                btn.classList.toggle('d-none', infografias.length <= cantidadInicial);
                btn.textContent = 'Ver más';
            })
            

            // Oculta todas primero excepto las iniciales
            infografias.forEach((inf, index) => {
                inf.classList.toggle('d-none', index >= cantidadInicial);
            });

            // Mostrar el botón solo si hay más de cantidadInicial infografías
            if (infografias.length > cantidadInicial) {
                btnVerMas.forEach(btn => {
                    btn.classList.remove('d-none');
                })
            } else {
                btnVerMas.forEach(btn => {
                    btn.classList.add('d-none');
                })
            }

            // Evento del botón "Ver más"
            btnVerMas.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!mostrandoTodo) {
                        mostrarInfografias(infografias.length); // Mostrar todas
                        btn.textContent = 'Ocultar';
                        mostrandoTodo = true;

                    } else {
                        mostrarInfografias(cantidadInicial); // Ocultar a partir del 6
                        btn.textContent = 'Ver más';
                        mostrandoTodo = false;
                    }
                });
            })

        });
    }

    function mostrarBotonVolver(mostrar = false) {
        if (mostrar) {
            btnVolverCategorias.forEach(btnVovler => {
                btnVovler.classList.remove('d-none');
            });

            btnVolverCategoriasContainer.forEach(containerBtnVolver => {
                containerBtnVolver.classList.remove('d-none');
            })
            
        } else {
            btnVolverCategorias.forEach(btnVovler => {
                btnVovler.classList.add('d-none');
            })

            btnVolverCategoriasContainer.forEach(containerBtnVolver => {
                containerBtnVolver.classList.add('d-none');
            })
        }
    }


    function obtenerInfografias() {
        todasLasInfografias = [];
        const categorias = document.querySelectorAll('.category-group');
        
        categorias.forEach(categ => {
            const categoria = categ.getAttribute('data-category');
            const infografias = categ.querySelectorAll('.col_infographics');
            infografias.forEach(infografia => {
                todasLasInfografias.push({
                    nombre: infografia.getAttribute('data-name').toLowerCase(),
                    categoria,
                    elemento: infografia,
                    grupo: categ
                });

                activarBotonesDescarga(infografia); 
            });

            
        });
    }

    function ocultarTodasLasInfografias() {
        document.querySelectorAll('.category-group').forEach(grupo => grupo.classList.add('d-none'));
        document.querySelectorAll('.col_infographics').forEach(infografia => infografia.classList.add('d-none'));
    }

    function estadoInicial() {
        inputKeyword.value = '';
        inputCategory.value = '';
        titleResultados.textContent = '';
        containerResultados.classList.add('d-none');
        containerResultados.innerHTML = '';
        rowCategories.classList.remove('d-none');
        rowInfographics.classList.remove('d-none'); 

        ocultarTodasLasInfografias();
    }

    // FILTRO POR CATEGORÍA
    inputCategory.addEventListener('change', (e) => {
        const categoriaSeleccionada = e.target.value;

        titleResultados.textContent = '';
        containerResultados.classList.add('d-none');
        containerResultados.innerHTML = '';
        inputKeyword.value = '';
        rowCategories.classList.add('d-none');
        rowInfographics.classList.remove('d-none');

        ocultarTodasLasInfografias();

        if (categoriaSeleccionada === '' || categoriaSeleccionada === 'Todas') {
            estadoInicial();
        } else {
            const grupo = document.querySelector(`.category-group[data-category="${categoriaSeleccionada}"]`);
            if (grupo) {
                grupo.classList.remove('d-none');
                grupo.querySelectorAll('.col_infographics').forEach(infografia => infografia.classList.remove('d-none'));
                
                // Agregar función btn 'Ver más'
                configurarBotonesVerMas(); 
            } else {
                titleResultados.textContent = 'No se encontraron infografías de esa categoría.';
            }
        }
    });

    // FILTRO POR PALABRA CLAVE
    btnSearch.addEventListener('click', () => {
        const termino = inputKeyword.value.trim().toLowerCase();
        titleResultados.textContent = '';
        inputCategory.value = '';
        rowCategories.classList.add('d-none');
        rowInfographics.classList.add('d-none');
        containerResultados.classList.remove('d-none');
        containerResultados.innerHTML = '';

        ocultarTodasLasInfografias();

        if (termino === '') {
            titleResultados.textContent = 'Ingresa una palabra para buscar.';
            return;
        }

        const regex = new RegExp(termino.split('').join('.*'), 'i');
        const resultados = todasLasInfografias.filter(infografia => regex.test(infografia.nombre));

        if (resultados.length > 0) {
            resultados.forEach(res => {
                // containerResultados.appendChild(res.elemento.cloneNode(true))
                res.elemento.classList.remove('d-none');
                res.grupo.classList.remove('d-none'); 
                
            });


            rowInfographics.classList.remove('d-none'); 
            activarBotonesDescarga(containerResultados);
            borrarbtnVerMas();
            
            titleResultados.textContent = 'Resultados';

            
        } else {
            titleResultados.textContent = 'No se encontraron resultados.';
        }

        // Ocultamos el botón de volver
        mostrarBotonVolver(false);
    });

    // FUNCIONALIDAD BOTONES 'VER INFOGRAFÍAS DE LA SECCIÓN'
    document.querySelectorAll('.btn-show-inforaphics').forEach(btn => {
        btn.addEventListener('click', () => {
            const categoriaSeleccionada = btn.getAttribute('category-id');

            inputKeyword.value = '';
            inputCategory.value = categoriaSeleccionada;
            titleResultados.textContent = '';
            containerResultados.classList.add('d-none');
            containerResultados.innerHTML = '';
            rowCategories.classList.add('d-none');
            rowInfographics.classList.remove('d-none');

            ocultarTodasLasInfografias();

            const grupo = document.querySelector(`.category-group[data-category="${categoriaSeleccionada}"]`);
            if (grupo) {
                grupo.classList.remove('d-none');
                grupo.querySelectorAll('.col_infographics').forEach(infografia => infografia.classList.remove('d-none'));
            }
            // Mostrar botones 'volver a las categorías
            mostrarBotonVolver(true);

            // Mostrar bns 'ver más infografías
            configurarBotonesVerMas()
        });
    });

    // Funcionalidad 'volver a la sección de las infografías' 
    btnVolverCategorias.forEach( btnVolver => {
        btnVolver.addEventListener('click', () => {
        // Ocultamos todas las infografías
        document.querySelectorAll('.category-group').forEach(c => c.classList.add('d-none'));
        rowInfographics.classList.add('d-none');

        // Mostramos las cards de categorías
        rowCategories.classList.remove('d-none');

        // Ocultamos el botón de volver
        mostrarBotonVolver(false);

        // Limpiamos el título si lo deseas
        titleResultados.textContent = '';
        });
    })

    // FUNCIONALIDAD DESCARGAR IMAGEN
    function activarBotonesDescarga(contenedor) {
        contenedor.querySelectorAll('.descargar_img').forEach(btn => {
            btn.addEventListener('click', () => {
                const imgId = btn.id;
                const img = document.getElementById(imgId);
                console.log(img)
                if (img) descargarImagen(img.src);
            });
        });
    }

    function descargarImagen(url) {
        const link = document.createElement('a');
        link.href = url;
        link.download = obtenerNombreArchivo(url);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function obtenerNombreArchivo(url) {
        const partes = url.split('/');
        return partes.pop();
    }

    // REINICIAR FILTROS
    btnReset.addEventListener('click', () => {
        estadoInicial();
    });

    obtenerInfografias();
    estadoInicial();
});

