document.addEventListener('DOMContentLoaded', () => {
    // Contador clicks logos
    const logos = document.querySelectorAll('.aliados-b'); 
    let clics = {};
    
    logos.forEach(logo => {
        logo.addEventListener("click", () => {    
            const targetLogo = logo.getAttribute('data-bs-target');

            if (targetLogo && targetLogo.trim() !== '') {
                const empresa = targetLogo.substring(1);

                if (!clics[empresa]) {
                    clics[empresa] = 0; // Inicializar el contador si no existe
                }

                clics[empresa]++;
                // console.log(empresa + ': ' + clics[empresa]);
                // console.log('ajax_object.ajax_url:', ajax_object.ajax_url);
                // console.log('Datos a enviar:', empresa, clics[empresa]);

                // Enviar la solicitud AJAX para actualizar la base de datos
                fetch(ajax_object.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: `action=record_click&empresa=${empresa}&clics=${clics[empresa]}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud AJAX');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        console.error(data.message || 'Error desconocido', data.error || ''); 
                        
                    } 
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error.message, error);
                })
                
            }
        });
    });
});


