
<?php

require_once '../config/conexao.php';
require_once '../controllers/AgendamentoController.php';

$conexao = new conexao();
$pdo = $conexao->conectar();

$controller = new AgendamentoController($pdo);

session_start();

$mensagem = $_SESSION['agendamento_mensagem'] ?? null;
$sucesso = $_SESSION['agendamento_sucesso'] ?? false;

unset($_SESSION['agendamento_mensagem']);
unset($_SESSION['agendamento_sucesso']);

// Data selecionada
$dataSelecionada = $_GET['data'] ?? date('Y-m-d');

// Busca os horários disponíveis
$horariosDisponiveis = $controller->horariosDisponiveis($dataSelecionada);

// Todos os horários
$horarios = $controller->getHorarios();

?>

<div class="container-fluid">

    <?php if ($mensagem): ?>

        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show">

            <?= htmlspecialchars($mensagem) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>

    <!-- Título -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Agendamento
            </h1>

            <p class="text-muted mb-0">
                Escolha uma data e um horário para realizar seu agendamento.
            </p>
        </div>

    </div>


    <!-- Formulário -->
    <div class="card shadow-sm">

        <div class="card-body">

            <form id="formAgendamento" method="POST" action="controllers/AgendamentoController.php">

                <!-- Nome -->
                <div class="mb-3">

                    <label for="nome" class="form-label">
                        Nome
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        placeholder="Digite seu nome"
                        required
                    >

                </div>


                <!-- Telefone -->
                <div class="mb-3">

                    <label for="telefone" class="form-label">
                        Telefone
                    </label>

                    <input
                        type="tel"
                        class="form-control"
                        id="telefone"
                        name="telefone"
                        placeholder="(11) 99999-9999"
                        maxlength="15"
                        inputmode="numeric"
                        required
                    >

                </div>


                <!-- Data -->
                <div class="mb-4">

                    <label for="data" class="form-label">
                        Escolha a data
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="data"
                        name="data"
                        value="<?= htmlspecialchars($dataSelecionada) ?>"
                        min="<?= date('Y-m-d') ?>"
                        required
                    >

                    <small class="text-muted">
                        Domingos não estão disponíveis para agendamento.
                    </small>

                </div>


                
                

                    <!-- Horários --> 
                    <div class="mb-4"> 
                            <label class="form-label"> 
                                Escolha o horário 
                            </label> 
                            
                        <div id="listaHorarios" class="row g-2" > 
                            <?php foreach ($horarios as $hora): ?>

                                <?php $disponivel = in_array($hora, $horariosDisponiveis); ?>

                                <div class="col-6 col-md-3 col-lg-2">

                                    <button
                                        type="button"
                                        class="btn <?= $disponivel ? 'btn-outline-primary' : 'btn-secondary' ?> w-100"
                                        data-hora="<?= htmlspecialchars($hora) ?>"
                                        <?= !$disponivel ? 'disabled' : '' ?>
                                    >
                                        <?= htmlspecialchars($hora) ?>
                                    </button>

                                </div>

                            <?php endforeach; ?> 
                        </div>
                    
                    <!-- Horário escolhido --> 
                    
                    <input type="hidden" name="hora" id="hora" required > 
                    </div>

                <!-- Botão -->
                <div class="text-end">

                    
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Confirmar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


