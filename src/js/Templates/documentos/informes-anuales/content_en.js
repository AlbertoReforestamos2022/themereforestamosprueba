document.addEventListener('DOMContentLoaded', ()=> {
    // contenido localStorage
    const idiomaSeleccionado = localStorage.getItem("idioma");
    let titulo = document.querySelector('.title-general');
    let titulo_en = document.querySelector('.title-principal-en'); 
    let content_es = document.querySelector('.con-doc');
    let content_en = document.querySelector('.container_documents_en'); 

    if(idiomaSeleccionado == 'en-US'){
        titulo.textContent = titulo_en.textContent;  
        content_es.classList.add('d-none');
        
        let class_content_en = content_en.classList.contains('d-none'); 
        
        if(class_content_en) {
            content_en.classList.remove('d-none')
        }
        
    }

})