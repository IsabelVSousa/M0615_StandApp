document.addEventListener('DOMContentLoaded', function () {

    const estado = localStorage.getItem('cookiesAceptadas');

    if (estado === 'true') {
        mostrarLoginNormal();
        return;
    }

    if (estado === 'false') {
        mostrarBotonReactivar();
        return;
    }

    // Primera visita
    mostrarBanner();

});


function mostrarBanner() {
    ocultarLogin();

    if (!document.getElementById('cookie-banner')) {
        document.body.insertAdjacentHTML('beforeend', `
            <style>
                #cookie-banner {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro semitransparente que bloquea la web */
                    z-index: 99999;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                #cookie-content {
                    background-color: #1e1e1e; /* Color oscuro a juego con tu web */
                    color: #ffffff;
                    padding: 30px;
                    border-radius: 15px;
                    width: 90%;
                    max-width: 500px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                    text-align: center;
                    border: 1px solid #333;
                }
                #cookie-icon {
                    font-size: 3rem;
                    color: #ff9f43; /* Color galleta naranja/dorado */
                    margin-bottom: 15px;
                }
                #cookie-text h3 {
                    margin-bottom: 10px;
                    font-size: 1.5rem;
                }
                #cookie-text p {
                    color: #ccc;
                    font-size: 0.95rem;
                    line-height: 1.5;
                    margin-bottom: 20px;
                }
                #cookie-buttons {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 10px;
                }
                #cookie-buttons .btn, 
                #cookie-buttons .btn-book {
                    display: inline-block;
                    width: 140px;          
                    padding: 12px 0;       
                    text-align: center;
                    border-radius: 8px;    
                    font-weight: 600;
                    font-size: 0.95rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    box-sizing: border-box; 
                }

                #cookie-buttons button {
                margin: 0 !important; 
                  }
            </style>

            <div id="cookie-banner">
                <div id="cookie-content">
                    <div id="cookie-icon"><i class="fas fa-cookie-bite"></i></div>
                    <div id="cookie-text">
                        <h3>Usamos cookies</h3>
                        <p>Utilizamos cookies para mejorar tu experiencia y personalizar el contenido.
                        Para poder iniciar sesión necesitas aceptar el uso de cookies.</p>
                    </div>
                    <div id="cookie-buttons">
                        <button id="btn-rechazar-cookies" class="btn btn-outline" onclick="rechazarCookies()">Rechazar</button>
                        <button id="btn-aceptar-cookies" class="btn-book" onclick="aceptarCookies()">Aceptar</button>
                    </div>
                </div>
            </div>
        `);
    }

    document.getElementById('cookie-banner').style.display = 'flex'; // Cambiado a 'flex' para que el centrado funcione correctamente
}

function aceptarCookies() {
    localStorage.setItem('cookiesAceptadas', 'true');
    document.getElementById('cookie-banner').style.display = 'none';
    mostrarLoginNormal();
}

function rechazarCookies() {
    localStorage.setItem('cookiesAceptadas', 'false');
    document.getElementById('cookie-banner').style.display = 'none';
    mostrarBotonReactivar();
}

function mostrarLoginNormal() {
    const btnReactivar = document.getElementById('btn-reactivar-cookies');
    if (btnReactivar) btnReactivar.remove();

    const btnLogin = document.querySelector('.nav-buttons .btn-outline');
    if (btnLogin) btnLogin.style.display = 'inline-block';
}

function ocultarLogin() {
    const btnLogin = document.querySelector('.nav-buttons .btn-outline');
    if (btnLogin) btnLogin.style.display = 'none';
}

function mostrarBotonReactivar() {
    ocultarLogin();

    if (!document.getElementById('btn-reactivar-cookies')) {
        const btn = document.createElement('a');
        btn.id = 'btn-reactivar-cookies';
        btn.className = 'btn btn-outline';
        btn.style.cursor = 'pointer';
        btn.innerHTML = '<i class="fas fa-cookie-bite" style="margin-right:6px;"></i>Cookies';
        btn.addEventListener('click', function () {
            btn.remove();
            mostrarBanner();
        });

        document.querySelector('.nav-buttons').prepend(btn);
    }
}
