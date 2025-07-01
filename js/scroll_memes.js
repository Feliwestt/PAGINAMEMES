// Guardar la posición de scroll antes de salir
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.comentario-link').forEach(link => {
        link.addEventListener('click', function() {
            localStorage.setItem('scrollPosMemes', window.scrollY);
        });
    });
    // Restaurar la posición de scroll al cargar la galería
    const pos = localStorage.getItem('scrollPosMemes');
    if (pos !== null) {
        window.scrollTo(0, parseInt(pos));
        localStorage.removeItem('scrollPosMemes');
    }
}); 