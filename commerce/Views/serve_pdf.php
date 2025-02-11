<?php
// Verificar si se ha proporcionado el nombre del archivo
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Asegurarse de que no haya caracteres peligrosos
    $filePath = '../Util/Pdf/' . $file;

    // Verificar si el archivo existe
    if (file_exists($filePath)) {
        // Establecer las cabeceras adecuadas para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo 'Archivo no encontrado.';
    }
} else {
    echo 'Nombre de archivo no proporcionado.';
}
?>