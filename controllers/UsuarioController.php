
<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

// Verifica se o usuário está autenticado
if (
    empty($_SESSION['usuario_logado']) ||
    empty($_SESSION['usuario_id'])
) {
    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Usuário não autenticado.'
    ]);
    exit;
}

// Permite somente POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido.'
    ]);
    exit;
}

// Recebe os dados do formulário
$senhaAtual = $_POST['senha_atual'] ?? '';
$novaSenha = $_POST['nova_senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';

if (
    $senhaAtual === '' ||
    $novaSenha === '' ||
    $confirmarSenha === ''
) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos.'
    ]);
    exit;
}

if (strlen($novaSenha) < 8) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'A nova senha deve ter pelo menos 8 caracteres.'
    ]);
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'As novas senhas não coincidem.'
    ]);
    exit;
}

try {

    $conexao = new conexao();
    $pdo = $conexao->conectar();

    $usuarioModel = new UsuarioModel($pdo);

    $usuario = $usuarioModel->buscarPorId(
        (int) $_SESSION['usuario_id']
    );

    if (!$usuario) {
        throw new Exception('Usuário não encontrado.');
    }

    // Verifica a senha atual
    if (!password_verify($senhaAtual, $usuario['senha'])) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Senha atual incorreta.'
        ]);
        exit;
    }

    // Impede reutilizar a senha atual
    if (password_verify($novaSenha, $usuario['senha'])) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'A nova senha deve ser diferente da atual.'
        ]);
        exit;
    }

    // Gera um hash seguro
    $novaSenhaHash = password_hash(
        $novaSenha,
        PASSWORD_DEFAULT
    );

    $usuarioModel->alterarSenha(
        (int) $_SESSION['usuario_id'],
        $novaSenhaHash
    );

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Senha alterada com sucesso!'
    ]);

} catch (Throwable $erro) {

    error_log($erro->getMessage());

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao alterar a senha.'
    ]);
}
