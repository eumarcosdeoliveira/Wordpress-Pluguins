jQuery(document).ready(function($) {
    document.addEventListener('wpcf7invalid', function(event) {
        if (event.detail && event.detail.apiResponse) {
            console.log('API Response:', event.detail.apiResponse);
        }
        // Adiciona instrução de depuração para mostrar que o evento wpcf7invalid foi acionado
        console.log('Evento wpcf7invalid acionado.');
    });
});
