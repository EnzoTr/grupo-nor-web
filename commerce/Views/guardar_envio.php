<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['envio']) && isset($_POST['direccion'])) {
        // Guardar el costo de envío en la sesión
        $_SESSION['costo_envio'] = floor($_POST['envio']);
        // Guardar la dirección seleccionada en la sesión
        $_SESSION['direccion_envio'] = $_POST['direccion'];

        // Devolver el costo de envío y la dirección al cliente
        echo json_encode([
            'status' => 'success', 
            'costo_envio' => $_SESSION['costo_envio'],
            'direccion' => $_SESSION['direccion_envio']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibieron todos los datos necesarios']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido']);
}
?>