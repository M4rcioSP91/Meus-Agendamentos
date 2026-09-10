<?php

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../controllers/GaleriaController.php";

header('Content-Type: application/json');

try {

    // Verifica se recebeu o ID
    if (!isset($_POST['id'])) {

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'ID da imagem não informado.'
        ]);

        exit;
    }


    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);


    if (!$id) {

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'ID da imagem inválido.'
        ]);

        exit;
    }


    // Conexão

    $conexao = new conexao();

    $pdo = $conexao->conectar();


    // Controller

    $galeria = new GaleriaController($pdo);


    // Exclui a imagem

    $resultado = $galeria->excluir($id);


    echo json_encode($resultado);


} catch (Exception $e) {

    echo json_encode([
        'sucesso' => false,
        'mensagem' => $e->getMessage()
    ]);

}