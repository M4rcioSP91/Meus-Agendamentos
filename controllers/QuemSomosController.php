
<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

// Somente usuários autenticados podem editar.
if (empty($_SESSION['usuario_logado'])) {
    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Você precisa estar logado para editar a descrição.'
    ]);

    exit;
}

// Aceita somente requisições POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido.'
    ]);

    exit;
}

require_once __DIR__ . '/../config/conexao.php';

$descricao = trim($_POST['descricao'] ?? '');

if ($descricao === '') {
    http_response_code(422);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'A descrição não pode ficar vazia.'
    ]);

    exit;
}

if (mb_strlen($descricao) > 10000) {
    http_response_code(422);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'A descrição deve ter no máximo 10.000 caracteres.'
    ]);

    exit;
}

try {
    $conexao = new conexao();
    $pdo = $conexao->conectar();

    $sql = "UPDATE tb_quem_somos
            SET descricao = :descricao
            WHERE id = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->execute();

    // Se a linha inicial tiver sido removida, recria o registro.
    if ($stmt->rowCount() === 0) {
        $consulta = $pdo->query(
            "SELECT id FROM tb_quem_somos WHERE id = 1"
        );

        if (!$consulta->fetch()) {
            $stmt = $pdo->prepare(
                "INSERT INTO tb_quem_somos (id, descricao)
                 VALUES (1, :descricao)"
            );

            $stmt->bindValue(':descricao', $descricao);
            $stmt->execute();
        }
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Descrição atualizada com sucesso!'
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível salvar a descrição.'
    ]);
}
