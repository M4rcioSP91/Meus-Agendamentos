<?php

session_start();

require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    $_SESSION['mensagem_recuperacao'] = 'Preencha todos os campos.';
    header('Location: ../pages/recuperar_senha.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensagem_recuperacao'] = 'E-mail inválido.';
    header('Location: ../pages/recuperar_senha.php');
    exit;
}

if (strlen($senha) < 8) {
    $_SESSION['mensagem_recuperacao'] = 'A senha deve ter pelo menos 8 caracteres.';
    header('Location: ../pages/recuperar_senha.php');
    exit;
}

if ($senha !== $confirmarSenha) {
    $_SESSION['mensagem_recuperacao'] = 'As senhas não correspondem.';
    header('Location: ../pages/recuperar_senha.php');
    exit;
}

try {

    $conexao = new conexao();
    $pdo = $conexao->conectar();

    $sql = "SELECT id FROM tb_usuarios
            WHERE nome = :nome AND email = :email";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $_SESSION['mensagem_recuperacao'] = 'Dados não encontrados. Verifique seu nome e e-mail.';
        header('Location: ../pages/recuperar_senha.php');
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE tb_usuarios
            SET senha = :senha
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':senha' => $senhaHash,
        ':id' => $usuario['id']
    ]);

    $_SESSION['mensagem_login'] = 'Senha alterada com sucesso! Faça login com sua nova senha.';

    header('Location: ../pages/login.php');
    exit;

} catch (PDOException $e) {

    error_log($e->getMessage());

    $_SESSION['mensagem_recuperacao'] = 'Não foi possível alterar a senha. Tente novamente.';

    header('Location: ../pages/recuperar_senha.php');
    exit;
}