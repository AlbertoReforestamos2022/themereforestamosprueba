
document.addEventListener("DOMContentLoaded", function () {
    const categoryFilter = document.getElementById("categoryFilter");
    const keywordFilter = document.getElementById("keywordFilter");
    const searchButton = document.getElementById("searchButton");
    const titleResults = document.getElementById("title_results");
    const categoryTitle = document.querySelectorAll("#category_title");
    const contenedoresInfografia = document.querySelectorAll('.row_infographics');

    /* Iniciar el valor por defecto cuando carga la página  */
    categoryFilter.value = "Todas"; // Valor por defecto

    // Función para aplicar filtros
    function applyFilters() {
        let valueFilter = categoryFilter.value;
        let selectedOption = categoryFilter.options[categoryFilter.selectedIndex].textContent;
        let keyword = keywordFilter.value.trim().toLowerCase();
        const contenedorTodas = document.getElementById('contenedorTodas');

        // Resetear
        contenedorTodas.innerHTML = '';
        contenedorTodas.style.display = 'none';
        eliminarFlechas(contenedorTodas);
        const btnAntiguo = contenedorTodas.querySelector('.btn-ver-mas, .btn-ver-menos');
        if (btnAntiguo) btnAntiguo.remove();

        contenedoresInfografia.forEach(c => c.style.display = 'none');
        categoryTitle.forEach(title => title.classList.add('d-none'));

        let visiblesGlobal = [];

        contenedoresInfografia.forEach(contenedor => {
            const infografias = contenedor.querySelectorAll('.col_infographics');
            let visibles = [];

            infografias.forEach(colInfographic => {
                const valueCategory = colInfographic.getAttribute('data-category').toLowerCase();
                const valueName = colInfographic.getAttribute('data-name').toLowerCase();

                const matchesCategory = valueFilter === "Todas" || valueCategory === valueFilter.toLowerCase();
                const matchesKeyword = keyword === "" || valueName.includes(keyword) || valueCategory.includes(keyword);

                if(matchesKeyword) {
                    valueFilter = ''; 
                    colInfographic.classList.remove('d-none');
                    visibles.push(colInfographic);
                    visiblesGlobal.push(colInfographic);
                } else {
                    colInfographic.classList.add('d-none');
                }

                if (matchesCategory) {
                    colInfographic.classList.remove('d-none');
                    visibles.push(colInfographic);
                    visiblesGlobal.push(colInfographic);
                } else {
                    colInfographic.classList.add('d-none');
                }
            });

            if (valueFilter !== "Todas" && visibles.length > 0) {
                contenedor.style.display = '';
            }
        });

        // Si es "Todas", agrupar en contenedorTodas
        if (valueFilter === "Todas") {
            contenedorTodas.style.display = 'flex';

            visiblesGlobal.forEach((el, index) => {
                const clon = el.cloneNode(true);
                if (index >= 5) clon.classList.add('d-none');
                contenedorTodas.appendChild(clon);
            });

            if (visiblesGlobal.length > 5) {
                const btnToggle = document.createElement('button');
                btnToggle.textContent = 'Ver más';
                btnToggle.classList.add('btn', 'btn-secondary', 'text-white', 'p-2', 'mt-3', 'btn-ver-mas');

                let flechasAgregadas = false;

                btnToggle.addEventListener('click', () => {
                    const ocultas = contenedorTodas.querySelectorAll('.col_infographics.d-none');

                    if (ocultas.length > 0) {
                        ocultas.forEach(el => el.classList.remove('d-none'));
                        btnToggle.textContent = 'Ver menos';
                        btnToggle.classList.remove('btn-ver-mas');
                        btnToggle.classList.add('btn-ver-menos');

                        if (!flechasAgregadas) {
                            agregarFlechas(contenedorTodas);
                            flechasAgregadas = true;
                        }
                    } else {
                        contenedorTodas.querySelectorAll('.col_infographics').forEach((el, index) => {
                            if (index >= 5) el.classList.add('d-none');
                        });
                        btnToggle.textContent = 'Ver más';
                        btnToggle.classList.remove('btn-ver-menos');
                        btnToggle.classList.add('btn-ver-mas');
                        eliminarFlechas(contenedorTodas);
                        flechasAgregadas = false;
                    }
                });

                contenedorTodas.appendChild(btnToggle);
            }
        }

        // Mostrar texto de resultados
        if (keyword !== "") {
            titleResults.innerText = visiblesGlobal.length > 0
                ? `Resultados sobre "${keyword}"`
                : `No encontramos resultados sobre "${keyword}"`;
        } else {
            titleResults.innerText = visiblesGlobal.length > 0
                ? `${selectedOption}`
                : `No encontramos resultados sobre "${selectedOption}"`;

                if(valueFilter === 'Todas') {
                    titleResults.innerText = `Todas las infografías`;
                }
        }
    }
    

    // Eventos
    categoryFilter.addEventListener("change", applyFilters);
    searchButton.addEventListener("click", applyFilters);
    keywordFilter.addEventListener("keyup", function (e) {
        if (e.key === "Enter") {
            applyFilters();
        }
    });

    // Funciones para flechas
    function agregarFlechas(contenedor) {
        const btnLeft = document.createElement('button');
        const btnRight = document.createElement('button');

        btnLeft.innerHTML = '<i class="bi bi-arrow-left"></i>';
        btnRight.innerHTML = '<i class="bi bi-arrow-right"></i>';

        [btnLeft, btnRight].forEach(btn => {
            btn.style.position = 'absolute';
            btn.style.width = '30px';
            btn.style.height = '30px';
            btn.style.top = '50%';
            btn.style.transform = 'translateY(-50%)';
            btn.style.display = 'grid';
            btn.style.alignContent = 'center';
            btn.style.justifyContent = 'center';
            btn.style.zIndex = '10';
            btn.style.backgroundColor = '#b1cb36';
            btn.style.color = 'white';
            btn.style.border = 'none';
            btn.style.borderRadius = '5px';
            btn.style.padding = '0.5rem 1rem';
            btn.style.cursor = 'pointer';
        });

        btnLeft.style.left = '0';
        btnRight.style.right = '0';

        btnLeft.addEventListener('click', () => {
            contenedor.scrollBy({ left: -300, behavior: 'smooth' });
        });

        btnRight.addEventListener('click', () => {
            contenedor.scrollBy({ left: 300, behavior: 'smooth' });
        });

        contenedor.appendChild(btnLeft);
        contenedor.appendChild(btnRight);
    }

    function eliminarFlechas(contenedor) {
        const btnLeft = contenedor.querySelector('button i.bi-arrow-left')?.parentElement;
        const btnRight = contenedor.querySelector('button i.bi-arrow-right')?.parentElement;
        if (btnLeft) btnLeft.remove();
        if (btnRight) btnRight.remove();
    }

    // Aplicar inicialmente
    applyFilters();
});

