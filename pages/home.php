
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexao.php';

$conexao = new conexao();
$pdo = $conexao->conectar();

$stmt = $pdo->query(
    "SELECT descricao FROM tb_quem_somos WHERE id = 1"
);

$quemSomos = $stmt->fetch(PDO::FETCH_ASSOC);

$descricao = $quemSomos['descricao'] ??
    'Nossa descrição será adicionada em breve.';

$usuarioLogado = !empty($_SESSION['usuario_logado']);
?>


<!-- SEÇÕES: QUEM SOMOS E LOCALIZAÇÃO -->
<div class="container-fluid py-4">

    <div class="row g-4">

        <!-- QUEM SOMOS: 3 COLUNAS -->
        <div class="col-12 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="bi bi-building me-2"></i>
                        Quem Somos
                    </h5>

                    <?php if ($usuarioLogado): ?>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            id="btnEditarQuemSomos"
                        >
                            <i class="bi bi-pencil-square"></i>
                            Editar
                        </button>
                    <?php endif; ?>
                </div>

                <div class="card-body">

                    <div id="textoQuemSomos">
                        <p class="mb-0" style="white-space: pre-line"><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <?php if ($usuarioLogado): ?>
                        <form id="formQuemSomos" class="d-none">

                            <div class="mb-3">
                                <label for="descricaoQuemSomos" class="form-label">
                                    Descrição da empresa
                                </label>

                                <textarea
                                    class="form-control"
                                    id="descricaoQuemSomos"
                                    name="descricao"
                                    rows="8"
                                    maxlength="10000"
                                    required
                                ><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Salvar
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm"
                                    id="btnCancelarQuemSomos"
                                >
                                    Cancelar
                                </button>
                            </div>

                        </form>
                    <?php endif; ?>

                    <div
                        id="mensagemQuemSomos"
                        class="alert d-none mt-3 mb-0"
                        role="alert"
                    ></div>

                </div>
            </div>

        </div>

        <!-- LOCALIZAÇÃO: 6 COLUNAS -->
        <div class="col-12 col-lg-9">

            <div class="card shadow-sm h-100">

                <div class="card-header py-3">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Localização
                    </h5>
                </div>

                <div class="card-body">

                    <p class="mb-3">
                        <i class="bi bi-geo-alt me-2"></i>
                        Av. Paulista, 352 - Bela Vista, São Paulo - SP, 01310-000
                    </p>

                    <div class="ratio ratio-4x3">
                        <iframe
                            src="https://maps.google.com/maps?q=Av.%20Paulista%2C%20352%20-%20Bela%20Vista%2C%20S%C3%A3o%20Paulo%20-%20SP%2C%2001310-000&output=embed"
                            title="Mapa da localização da empresa"
                            style="border: 0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

