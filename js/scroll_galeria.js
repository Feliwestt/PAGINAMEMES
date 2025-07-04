// Guarda la posición del scroll antes de salir de la galería
window.addEventListener('beforeunload', function() {
    sessionStorage.setItem('scrollGaleria', window.scrollY);
});

// Restaura la posición del scroll al cargar la galería
window.addEventListener('DOMContentLoaded', function() {
    const scroll = sessionStorage.getItem('scrollGaleria');
    if (scroll !== null) {
        window.scrollTo(0, parseInt(scroll, 10));
    }
}); 