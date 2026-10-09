<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/AgendamentoModel.php';

$conexao = new conexao();
$pdo = $conexao->conectar();

$model = new Agendamento($pdo);

$porPagina = 20;

$paginaAtual = filter_input(
    INPUT_GET,
    'pagina',
    FILTER_VALIDATE_INT
);

$paginaAtual = max(1, $paginaAtual ?: 1);

$totalAtendimentos = $model->contarTodosAtendimentos();

$totalPaginas = max(
    1,
    (int) ceil($totalAtendimentos / $porPagina)
);

$paginaAtual = min($paginaAtual, $totalPaginas);

$offset = ($paginaAtual - 1) * $porPagina;

$atendimentos = $model->listarAtendimentosPaginados(
    $porPagina,
    $offset
);

?>

<div class="container-fluid py-4">

    <h1 class="h3 mb-4 text-gray-800">
        Histórico de atendimentos
    </h1>

    <p>
        Total de atendimentos:
        <strong><?= $totalAtendimentos ?></strong>
    </p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Data</th>
                    <th>Horário</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($atendimentos)): ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Nenhum atendimento cadastrado.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($atendimentos as $atendimento): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($atendimento['nome_cliente']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($atendimento['telefone']) ?>
                            </td>

                            <td>
                                <?= date(
                                    'd/m/Y',
                                    strtotime($atendimento['data_agendamento'])
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    substr($atendimento['hora_agendamento'], 0, 5)
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINAÇÃO -->
    <?php if ($totalAtendimentos > $porPagina): ?>
        <nav aria-label="Paginação do histórico">

            <ul class="pagination justify-content-center">

                <li class="page-item <?= $paginaAtual <= 1 ? 'disabled' : '' ?>">
                    <a
                        class="page-link carregar-pagina"
                        href="pages/historico.php?pagina=<?= $paginaAtual - 1 ?>"
                    >
                        Anterior
                    </a>
                </li>

                <li class="page-item disabled">
                    <span class="page-link">
                        Página <?= $paginaAtual ?> de <?= $totalPaginas ?>
                    </span>
                </li>

                <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
                    <a
                        class="page-link carregar-pagina"
                        href="pages/historico.php?pagina=<?= $paginaAtual + 1 ?>"
                    >
                        Próxima
                    </a>
                </li>

            </ul>

        </nav>
    <?php endif; ?>

</div>