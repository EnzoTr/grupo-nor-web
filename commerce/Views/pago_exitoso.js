import { verificar_sesion } from "./sesion.js";

$(document).ready(function() {
    verificar_sesion();
});

function redireccionar() {
    // Redirige al usuario a tienda.php
    if (error || statusMP === 'rejected') {
        window.location.href = './carrito.php';
    } else {
        window.location.href = './mis_pedidos.php';
    }
}

// Tiempo en segundos antes de redireccionar al usuario
var segundos = 5;

// Espera {segundos} segundos antes de redireccionar al usuario
setTimeout(redireccionar, segundos * 1000); // Multiplica por 1000 para convertir segundos a milisegundos

// Actualiza el contador de segundos cada segundo
var contador = segundos;
var intervalo = setInterval(function() {
    contador--;
    document.getElementById('contador-segundos').textContent = contador;
    // Detiene el intervalo cuando el contador llega a cero
    if (contador === 0) {
        clearInterval(intervalo);
    }
}, 1000); // Actualiza el contador cada 1000 milisegundos (1 segundo)