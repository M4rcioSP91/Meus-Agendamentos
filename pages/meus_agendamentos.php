<?php

require_once '../config/conexao.php';
require_once '../controllers/AgendamentoController.php';

$conexao = new conexao();

$pdo = $conexao->conectar();

$controller = new AgendamentoController($pdo);


// =========================================================
// DATA SELECIONADA
// =========================================================

$hoje = date('Y-m-d');

$dataSelecionada = $_GET['data'] ?? $hoje;


// Impede acessar dias anteriores

if ($dataSelecionada < $hoje) {

    $dataSelecionada = $hoje;

}


// Busca os agendamentos

$agendamentos = $controller->listarPorData($dataSelecionada);


// Converte a data para exibição

$dataFormatada = date(
    'd/m/Y',
    strtotime($dataSelecionada)
);

?>

<div class="container-fluid" id="agendaDia" data-data="<?= htmlspecialchars($dataSelecionada) ?>">

    <!-- ================================================= -->
    <!-- TÍTULO -->
    <!-- ================================================= -->

    <div class="mb-4">

        <h1 class="h3 mb-1">
            Meus Agendamentos
        </h1>

        <p class="text-muted mb-0">
            Visualize os agendamentos por dia.
        </p>

    </div>


    <!-- ================================================= -->
    <!-- NAVEGAÇÃO DE DATA -->
    <!-- ================================================= -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <!-- BOTÃO ANTERIOR -->

                <div>

                    <?php if ($dataSelecionada > $hoje): ?>

                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            id="btnDiaAnterior"
                        >
                            <i class="bi bi-chevron-left"></i>
                            Anterior
                        </button>

                    <?php endif; ?>

                </div>


                <!-- DATA -->

                <div class="text-center">

                    <h4 class="mb-0">

                        <?= htmlspecialchars($dataFormatada) ?>

                    </h4>

                    <?php if ($dataSelecionada === $hoje): ?>

                        <small class="text-muted">
                            Hoje
                        </small>

                    <?php endif; ?>

                </div>


                <!-- BOTÃO PRÓXIMO -->

                <div>

                    <button
                        type="button"
                        class="btn btn-outline-primary"
                        id="btnProximoDia"
                    >
                        Próximo
                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ================================================= -->
    <!-- LISTA DE AGENDAMENTOS -->
    <!-- ================================================= -->

    <div class="card shadow-sm">

        <div class="card-body">

            <?php if (empty($agendamentos)): ?>

                <div class="text-center py-5">

                    <i
                        class="bi bi-calendar-x"
                        style="font-size: 3rem;"
                    ></i>

                    <h5 class="mt-3">
                        Nenhum agendamento
                    </h5>

                    <p class="text-muted mb-0">
                        Não existem agendamentos para este dia.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Horário
                                </th>

                                <th>
                                    Cliente
                                </th>

                                <th>
                                    Telefone
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($agendamentos as $agendamento): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $agendamento['hora_agendamento']
                                            ) ?>
                                        </strong>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars(
                                            $agendamento['nome_cliente']
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars(
                                            $agendamento['telefone']
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>
