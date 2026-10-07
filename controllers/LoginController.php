<?php

session_start();

require_once '../config/conexao.php';

$conexao = new conexao();
$pdo = $conexao->conectar();

$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

if (empty($email) || empty($senha)) {

    header('Location: ../pages/login.php?erro=preencha');
    exit;
}

$sql = "SELECT *
        FROM tb_usuarios
        WHERE email = :email
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($senha, $usuario['senha'])) {

    header('Location: ../pages/login.php?erro=login');
    exit;
}

/*
|--------------------------------------------------------------------------
| LOGIN REALIZADO
|--------------------------------------------------------------------------
*/

$_SESSION['usuario_logado'] = true;
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_email'] = $usuario['email'];

header('Location: ../index.php');
exit;