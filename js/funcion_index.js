// Modal de subir meme
const abrirModal = document.getElementById('abrirModal');
const cerrarModal = document.getElementById('cerrarModal');
const modalFondo = document.getElementById('modalFondo');
const modalFormulario = document.getElementById('modalFormulario');

function mostrarModal() {
    modalFondo.classList.add('activo');
    modalFormulario.classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function ocultarModal() {
    modalFondo.classList.remove('activo');
    modalFormulario.classList.remove('activo');
    document.body.style.overflow = '';
}
abrirModal.addEventListener('click', mostrarModal);
cerrarModal.addEventListener('click', ocultarModal);
modalFondo.addEventListener('click', ocultarModal);
// Cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') ocultarModal();
});

document.getElementById('logo-indie').onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Modal de imagen grande
const modalImgFondo = document.getElementById('modalImgFondo');
const modalImg = document.getElementById('modalImg');
const cerrarModalImg = document.getElementById('cerrarModalImg');
const imgModalGrande = document.getElementById('imgModalGrande');

function mostrarImgModal(src, alt) {
    imgModalGrande.src = src;
    imgModalGrande.alt = alt || 'Meme grande';
    modalImgFondo.classList.add('activo');
    modalImg.classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function ocultarImgModal() {
    modalImgFondo.classList.remove('activo');
    modalImg.classList.remove('activo');
    imgModalGrande.src = '';
    document.body.style.overflow = '';
}
cerrarModalImg.addEventListener('click', ocultarImgModal);
modalImgFondo.addEventListener('click', ocultarImgModal);
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') ocultarImgModal();
});
// Asignar evento a todas las imágenes de memes
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.meme-img-ampliable').forEach(function(img) {
        img.addEventListener('click', function() {
            mostrarImgModal(img.src, img.alt);
        });
        img.style.cursor = 'default';
    });
});

function toggleMenuOpciones(event, id) {
    event.stopPropagation();
    document.querySelectorAll('.menu-opciones').forEach(function(menu) {
        if (menu.id !== id) menu.classList.remove('activo');
    });
    var menu = document.getElementById(id);
    if (menu) menu.classList.toggle('activo');
}
document.addEventListener('click', function() {
    document.querySelectorAll('.menu-opciones').forEach(function(menu) {
        menu.classList.remove('activo');
    });
});
function descargarMeme(url, nombre) {
    const extension = url.split('.').pop().split('?')[0];
    const nombreArchivo = nombre.replace(/[^a-zA-Z0-9-_]/g, '_') + '.' + extension;
    const a = document.createElement('a');
    a.href = url;
    a.download = nombreArchivo;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Modal de reporte
const modalFondoReporte = document.getElementById('modalFondoReporte');
const modalReporte = document.getElementById('modalReporte');
const cerrarModalReporte = document.getElementById('cerrarModalReporte');
function abrirModalReporte(memeId) {
    document.getElementById('reporteMemeId').value = memeId;
    modalFondoReporte.style.display = 'block';
    modalReporte.style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function cerrarReporte() {
    modalFondoReporte.style.display = 'none';
    modalReporte.style.display = 'none';
    document.body.style.overflow = '';
}
cerrarModalReporte.addEventListener('click', cerrarReporte);
modalFondoReporte.addEventListener('click', cerrarReporte);
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarReporte();
}); 