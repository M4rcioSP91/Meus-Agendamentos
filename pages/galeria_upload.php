<?php

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../controllers/GaleriaController.php";

header('Content-Type: application/json');

try {

    // Verifica se foram enviadas imagens
    if (!isset($_FILES['imagens'])) {

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Nenhuma imagem foi enviada.'
        ]);

        exit;
    }

    // Conexão
    $conexao = new conexao();

    $pdo = $conexao->conectar();

    // Controller
    $galeria = new GaleriaController($pdo);

    // Faz o upload
    $resultado = $galeria->upload($_FILES['imagens']);

    echo json_encode([
        'sucesso' => true,
        'resultado' => $resultado
    ]);

} catch (Exception $e) {

    echo json_encode([
        'sucesso' => false,
        'mensagem' => $e->getMessage()
    ]);
}