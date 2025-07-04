function mostrarToast(mensaje) {
    const toast = document.getElementById('toast');
    toast.textContent = mensaje;
    toast.className = 'toast-mostrar';
    setTimeout(() => {
        toast.className = toast.className.replace('toast-mostrar', '');
    }, 3500);

    // Elimina el parámetro 'mensaje' de la URL
    if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('mensaje');
        window.history.replaceState({}, document.title, url);
    }
} 