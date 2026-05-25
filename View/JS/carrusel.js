/* ══ CARRUSEL EVENTOS ══ */

let posicion = 0;

function getCarrusel()      { return document.getElementById('carrusel'); }
function getDotsContainer() { return document.getElementById('carrusel-dots'); }

function itemsVisibles() {
    if (window.innerWidth <= 742) return 1;
    if (window.innerWidth <= 992) return 2;
    return 3;
}

function generarDots() {
    const carrusel      = getCarrusel();
    const dotsContainer = getDotsContainer();
    if (!carrusel || !dotsContainer) return;

    const totalItems = carrusel.children.length;
    const visibles   = itemsVisibles();
    const totalDots  = Math.max(1, totalItems - visibles + 1);

    dotsContainer.innerHTML = '';
    for (let i = 0; i < totalDots; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (i === posicion) dot.classList.add('activo');
        dot.addEventListener('click', () => irASlide(i));
        dotsContainer.appendChild(dot);
    }
}

function actualizarDots() {
    document.querySelectorAll('.dot').forEach((d, i) =>
        d.classList.toggle('activo', i === posicion)
    );
}

function getItemAncho(carrusel) {
    const primerItem = carrusel.children[0];
    const gap        = parseFloat(getComputedStyle(carrusel).gap) || 24;
    return primerItem ? primerItem.offsetWidth + gap : 0;
}

function actualizarCarrusel() {
    const carrusel = getCarrusel();
    if (!carrusel) return;

    const totalItems = carrusel.children.length;
    const visibles   = itemsVisibles();
    const maxPos     = Math.max(0, totalItems - visibles);
    posicion         = Math.min(posicion, maxPos);

    carrusel.style.transform = `translateX(-${posicion * getItemAncho(carrusel)}px)`;
    actualizarDots();
}

function moverCarrusel(direccion) {
    const carrusel = getCarrusel();
    if (!carrusel) return;

    const maxPos = Math.max(0, carrusel.children.length - itemsVisibles());
    posicion += direccion;
    if (posicion > maxPos) posicion = 0;
    if (posicion < 0)      posicion = maxPos;

    actualizarCarrusel();
}

function irASlide(index) {
    posicion = index;
    actualizarCarrusel();
}

function mostrarFecha(input) {
    if (input.value) {
        const partes = input.value.split('-');
        document.getElementById('fecha-texto').textContent =
            partes[2] + '/' + partes[1] + '/' + partes[0];
        input.form.submit();
    }
}


/* ══ CARRUSEL COMENTARIOS (autoplay, infinito, sin dots) ══
   Solo requiere añadir id="seccion-comentarios" al <section>
   El JS construye toda la estructura automáticamente.
══ */

let posComentarios = 0;
let autoplayTimer  = null;
const AUTOPLAY_MS  = 3000;

function itemsVisiblesComentarios() {
    if (window.innerWidth <= 742)  return 1;
    if (window.innerWidth <= 1065) return 2;
    return 3;
}

function construirCarruselComentarios() {
    const seccion = document.getElementById('seccion-comentarios');
    if (!seccion) return;

    const lista = seccion.querySelector('.collection-list');
    if (!lista) return;

    // Recoge las tarjetas existentes
    const tarjetas = Array.from(lista.children);
    if (tarjetas.length === 0) return;

    // Añade clase carrusel-item a cada tarjeta
    tarjetas.forEach(t => t.classList.add('carrusel-item'));

    // Construye el HTML del carrusel alrededor de las tarjetas
    const wrapper = document.createElement('div');
    wrapper.className = 'carrusel-wrapper';
    wrapper.id        = 'carrusel-comentarios-wrapper';

    const btnPrev = document.createElement('button');
    btnPrev.className = 'carrusel-btn carrusel-prev';
    btnPrev.innerHTML = '<i class="fas fa-chevron-left"></i>';
    btnPrev.addEventListener('click', () => moverComentarios(-1));

    const btnNext = document.createElement('button');
    btnNext.className = 'carrusel-btn carrusel-next';
    btnNext.innerHTML = '<i class="fas fa-chevron-right"></i>';
    btnNext.addEventListener('click', () => moverComentarios(1));

    const overflow = document.createElement('div');
    overflow.className = 'carrusel-overflow';

    const contenedor = document.createElement('div');
    contenedor.className = 'carrusel-contenedor';
    contenedor.id        = 'carrusel-comentarios';

    // Mueve las tarjetas dentro del contenedor
    tarjetas.forEach(t => contenedor.appendChild(t));

    overflow.appendChild(contenedor);
    wrapper.appendChild(btnPrev);
    wrapper.appendChild(overflow);
    wrapper.appendChild(btnNext);

    // Reemplaza el collection-list original con el nuevo wrapper
    lista.replaceWith(wrapper);
}

function actualizarCarruselComentarios() {
    const carrusel = document.getElementById('carrusel-comentarios');
    if (!carrusel) return;

    const totalItems = carrusel.children.length;
    const visibles   = itemsVisiblesComentarios();
    const maxPos     = Math.max(0, totalItems - visibles);

    posComentarios = ((posComentarios % (maxPos + 1)) + (maxPos + 1)) % (maxPos + 1);

    carrusel.style.transform = `translateX(-${posComentarios * getItemAncho(carrusel)}px)`;
}

function moverComentarios(direccion) {
    reiniciarAutoplay();
    posComentarios += direccion;
    actualizarCarruselComentarios();
}

function iniciarAutoplay() {
    autoplayTimer = setInterval(() => {
        posComentarios++;
        actualizarCarruselComentarios();
    }, AUTOPLAY_MS);
}

function reiniciarAutoplay() {
    clearInterval(autoplayTimer);
    iniciarAutoplay();
}


/* ══ INIT ══ */

document.addEventListener('DOMContentLoaded', () => {
    // Eventos
    generarDots();
    actualizarCarrusel();

    // Comentarios: construye el carrusel y arranca autoplay
    construirCarruselComentarios();
    actualizarCarruselComentarios();
    iniciarAutoplay();

    const wrapper = document.getElementById('carrusel-comentarios-wrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
        wrapper.addEventListener('mouseleave', iniciarAutoplay);
    }
});

window.addEventListener('resize', () => {
    generarDots();
    actualizarCarrusel();
    actualizarCarruselComentarios();
});
