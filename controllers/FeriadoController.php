
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../services/FeriadoService.php';

try {
    $data = $_GET['data'] ?? '';

    $service = new FeriadoService();
    $feriado = $service->verificar($data);

    echo json_encode([
        'sucesso' => true,
        'feriado' => $feriado !== null,
        'nome' => $feriado['name'] ?? null
    ]);

} catch (InvalidArgumentException $erro) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Data inválida.'
    ]);

} catch (Throwable $erro) {
    error_log($erro->getMessage());

    http_response_code(503);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível consultar os feriados.'
    ]);
}
