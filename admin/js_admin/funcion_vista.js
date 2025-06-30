/*  
   ✦ Cada bloque comprueba primero que los nodos existan
     para evitar TypeError cuando el HTML no los incluye.
   ✦ El zoom, el menú ⋮, la descarga y la lógica de eliminar
     funcionan en ambas vistas.
*/

// ────────────────────────────────────────────────────────────
// 1 Modal “Subir meme”  (solo está en la vista pública)
// ────────────────────────────────────────────────────────────
{
  const abrirModal      = document.getElementById('abrirModal');
  const cerrarModal     = document.getElementById('cerrarModal');
  const modalFondo      = document.getElementById('modalFondo');
  const modalFormulario = document.getElementById('modalFormulario');

  if (abrirModal && cerrarModal && modalFondo && modalFormulario) {
    const mostrarModal = () => {
      modalFondo.classList.add('activo');
      modalFormulario.classList.add('activo');
      document.body.style.overflow = 'hidden';
    };
    const ocultarModal = () => {
      modalFondo.classList.remove('activo');
      modalFormulario.classList.remove('activo');
      document.body.style.overflow = '';
    };

    abrirModal .addEventListener('click', mostrarModal);
    cerrarModal.addEventListener('click', ocultarModal);
    modalFondo  .addEventListener('click', ocultarModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') ocultarModal(); });
  }
}

// ────────────────────────────────────────────────────────────
// 2 Scroll al logo (existe en todas las vistas)
// ────────────────────────────────────────────────────────────
document.getElementById('logo-indie')?.addEventListener('click', () =>
  window.scrollTo({ top: 0, behavior: 'smooth' })
);

// ────────────────────────────────────────────────────────────
// 3 Modal de imagen / video ampliado (ambas vistas)
// ────────────────────────────────────────────────────────────
{
  const fondo  = document.getElementById('modalImgFondo');
  const modal  = document.getElementById('modalImg');
  const cerrar = document.getElementById('cerrarModalImg');
  const grande = document.getElementById('imgModalGrande');

  if (fondo && modal && cerrar && grande) {
    const abrirImg = (src, alt = 'Meme grande') => {
      grande.src = src;
      grande.alt = alt;
      fondo .classList.add('activo');
      modal .classList.add('activo');
      document.body.style.overflow = 'hidden';
    };
    const cerrarImg = () => {
      fondo .classList.remove('activo');
      modal .classList.remove('activo');
      grande.src = '';
      document.body.style.overflow = '';
    };

    cerrar.addEventListener('click', cerrarImg);
    fondo .addEventListener('click', cerrarImg);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarImg(); });

    // Añadir listener a cada imagen/video ampliable
    window.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.meme-img-ampliable').forEach(el => {
        el.addEventListener('click', () => abrirImg(el.src, el.alt));
        el.style.cursor = 'default';
      });
    });
  }
}

// ────────────────────────────────────────────────────────────
// 4 Menú “⋮” y descarga (ambas vistas)
// ────────────────────────────────────────────────────────────
function toggleMenuOpciones(event, id) {
  event.stopPropagation();
  document.querySelectorAll('.menu-opciones').forEach(m => {
    if (m.id !== id) m.classList.remove('activo');
  });
  document.getElementById(id)?.classList.toggle('activo');
}
document.addEventListener('click', () =>
  document.querySelectorAll('.menu-opciones').forEach(m => m.classList.remove('activo'))
);

function descargarMeme(url, nombre) {
  const extension = url.split('.').pop().split('?')[0];
  const fileName  = nombre.replace(/[^a-z0-9-_]/gi, '_') + '.' + extension;
  const a = document.createElement('a');
  a.href = url;
  a.download = fileName;
  document.body.appendChild(a);
  a.click();
  a.remove();
}

// ────────────────────────────────────────────────────────────
// 5 Modal de reporte (solo en vista pública)
// ────────────────────────────────────────────────────────────
{
  const modalFondoReporte = document.getElementById('modalFondoReporte');
  const modalReporte      = document.getElementById('modalReporte');
  const cerrarModalReporte= document.getElementById('cerrarModalReporte');

  if (modalFondoReporte && modalReporte && cerrarModalReporte) {
    const abrirModalReporte = memeId => {
      document.getElementById('reporteMemeId').value = memeId;
      modalFondoReporte.style.display = 'block';
      modalReporte     .style.display = 'block';
      document.body.style.overflow = 'hidden';
    };
    const cerrarReporte = () => {
      modalFondoReporte.style.display = 'none';
      modalReporte     .style.display = 'none';
      document.body.style.overflow = '';
    };

    window.abrirModalReporte = abrirModalReporte;      // ← exporta a global para inline‑JS
    cerrarModalReporte.addEventListener('click', cerrarReporte);
    modalFondoReporte .addEventListener('click', cerrarReporte);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarReporte(); });
  }
}

// ────────────────────────────────────────────────────────────
// 6 Eliminar meme (solo aparece en admin, pero no estorba en público)
// ────────────────────────────────────────────────────────────
function eliminarMeme(id){
  if (!confirm('¿Seguro que deseas borrar este meme?')) return;

  fetch('eliminar_meme.php', {
    method : 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body   : 'meme_id=' + encodeURIComponent(id)
  })
  .then(r => r.text())
  .then(t => {
    if (t.trim() === 'OK') {
      // si estamos en meme_admin.php → ir al panel
      if (location.pathname.includes('meme_admin.php'))
        location.href = 'vista_admin.php?msg=Meme+eliminado';
      else
        location.reload();
    } else {
      alert('Error:\n' + t);
    }
  })
  .catch(() => alert('Error al conectar con el servidor.'));
}

// Exportar algunas funciones globalmente para los atributos inline
window.toggleMenuOpciones = toggleMenuOpciones;
window.descargarMeme      = descargarMeme;
window.eliminarMeme       = eliminarMeme;
