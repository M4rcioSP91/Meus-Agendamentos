<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 450px;">

        <div class="text-center mb-4">
            <i class="bi bi-key-fill fs-1"></i>
            <h3>Recuperar senha</h3>
            <p class="text-muted">Preencha os dados para cadastrar uma nova senha.</p>
        </div>

        <?php if (isset($_SESSION['mensagem_recuperacao'])): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($_SESSION['mensagem_recuperacao']) ?>
            </div>
            <?php unset($_SESSION['mensagem_recuperacao']); ?>
        <?php endif; ?>

        <form action="../controllers/RecuperarSenhaController.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Nome cadastrado</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail cadastrado</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nova senha</label>
                <input type="password" name="senha" class="form-control" minlength="8" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar nova senha</label>
                <input type="password" name="confirmar_senha" class="form-control" minlength="8" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Alterar senha
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="../login.php" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Voltar para o login
            </a>
        </div>

    </div>

</div>

</body>
</html>
