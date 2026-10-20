
<?php

require_once __DIR__ . '/../models/AgendamentoModel.php';
require_once __DIR__ . '/../services/FeriadoService.php';

class AgendamentoController
{
    private $agendamento;

    private $horarios = [
        '08:00',
        '09:00',
        '10:00',
        '11:00',
        '12:00',
        '13:00',
        '14:00',
        '15:00',
        '16:00',
        '17:00'
    ];

    // =========================================================
    // CONSTRUTOR
    // =========================================================

    public function __construct($pdo)
    {
        $this->agendamento = new Agendamento($pdo);
    }

    // =========================================================
    // CONSULTAR FERIADOS - BRASILAPI
    // =========================================================

    private function consultarFeriado($data)
    {
        $feriadoService = new FeriadoService();

        return $feriadoService->verificar($data);
    }

    // =========================================================
    // RETORNAR HORÁRIOS DISPONÍVEIS
    // =========================================================

    public function horariosDisponiveis($data)
    {
        if (!$this->dataValida($data)) {
            return [];
        }

        if ($data < date('Y-m-d')) {
            return [];
        }

        if ($this->ehDomingo($data)) {
            return [];
        }

        // Bloqueia feriados nacionais
        $feriado = $this->consultarFeriado($data);

        if ($feriado !== null) {
            return [];
        }

        // Consulta os horários ocupados
        $ocupados = $this->agendamento->horariosOcupados($data);

        $disponiveis = array_diff(
            $this->horarios,
            $ocupados
        );

        return array_values($disponiveis);
    }

    // =========================================================
    // LISTAR AGENDAMENTOS
    // =========================================================

    public function listarPorData($data)
    {
        $hoje = date('Y-m-d');

        if (empty($data) || $data < $hoje) {
            $data = $hoje;
        }

        return $this->agendamento->listarPorData($data);
    }

    // =========================================================
    // CRIAR AGENDAMENTO
    // =========================================================

    public function criar($nome, $telefone, $data, $hora)
    {
        // Valida nome
        if (empty(trim($nome))) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe seu nome.'
            ];
        }

        // Valida telefone
        if (empty(trim($telefone))) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe seu telefone.'
            ];
        }

        // Valida data
        if (!$this->dataValida($data)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Data inválida.'
            ];
        }

        // Impede datas anteriores
        if ($data < date('Y-m-d')) {
            return [
                'sucesso' => false,
                'mensagem' =>
                    'Não é possível agendar uma data que já passou.'
            ];
        }

        // Impede domingos
        if ($this->ehDomingo($data)) {
            return [
                'sucesso' => false,
                'mensagem' =>
                    'Não é possível realizar agendamentos aos domingos.'
            ];
        }

        // Valida horário
        if (!in_array($hora, $this->horarios, true)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Horário inválido.'
            ];
        }

        // =====================================================
        // VERIFICAR FERIADOS - BRASILAPI
        // =====================================================

        try {

            $feriado = $this->consultarFeriado($data);

            if ($feriado !== null) {
                return [
                    'sucesso' => false,
                    'mensagem' =>
                        'Não é possível agendar nesta data: '
                        . $feriado['name'] . '.'
                ];
            }

        } catch (Throwable $erro) {

            error_log($erro->getMessage());

            return [
                'sucesso' => false,
                'mensagem' =>
                    'Não foi possível verificar os feriados. '
                    . 'Tente novamente.'
            ];
        }

        // Verifica se o horário está ocupado
        if ($this->agendamento->horarioOcupado($data, $hora)) {
            return [
                'sucesso' => false,
                'mensagem' =>
                    'Esse horário já foi agendado. Escolha outro horário.'
            ];
        }

        // Salva no banco
        $resultado = $this->agendamento->criar(
            $nome,
            $telefone,
            $data,
            $hora
        );

        if ($resultado) {
            return [
                'sucesso' => true,
                'mensagem' => 'Agendamento realizado com sucesso!'
            ];
        }

        return [
            'sucesso' => false,
            'mensagem' =>
                'Não foi possível realizar o agendamento.'
        ];
    }

    // =========================================================
    // EXCLUIR AGENDAMENTO
    // =========================================================

    public function excluir($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Agendamento inválido.'
            ];
        }

        $resultado = $this->agendamento->excluir((int) $id);

        if ($resultado) {
            return [
                'sucesso' => true,
                'mensagem' => 'Agendamento excluído com sucesso.'
            ];
        }

        return [
            'sucesso' => false,
            'mensagem' =>
                'Não foi possível excluir o agendamento.'
        ];
    }

    // =========================================================
    // RETORNAR TODOS OS HORÁRIOS
    // =========================================================

    public function getHorarios()
    {
        return $this->horarios;
    }

    // =========================================================
    // VALIDAR DATA
    // =========================================================

    private function dataValida($data)
    {
        if (!is_string($data)) {
            return false;
        }

        $dataObjeto = DateTime::createFromFormat(
            '!Y-m-d',
            $data
        );

        return $dataObjeto &&
               $dataObjeto->format('Y-m-d') === $data;
    }

    // =========================================================
    // VERIFICAR DOMINGO
    // =========================================================

    private function ehDomingo($data)
    {
        $dataObjeto = new DateTime($data);

        return $dataObjeto->format('w') == 0;
    }
}

// =============================================================
// BUSCAR HORÁRIOS DISPONÍVEIS - GET
// =============================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    isset($_GET['buscar_horarios'])
) {

    require_once __DIR__ . '/../config/conexao.php';

    header('Content-Type: application/json; charset=utf-8');

    try {

        $conexao = new conexao();
        $pdo = $conexao->conectar();

        $controller = new AgendamentoController($pdo);

        $data = $_GET['data'] ?? '';

        $horariosDisponiveis =
            $controller->horariosDisponiveis($data);

        echo json_encode([
            'sucesso' => true,
            'horarios' => $horariosDisponiveis
        ]);

    } catch (Throwable $erro) {

        error_log($erro->getMessage());

        http_response_code(503);

        echo json_encode([
            'sucesso' => false,
            'mensagem' =>
                'Não foi possível verificar a disponibilidade.'
        ]);
    }

    exit;
}

// =============================================================
// PROCESSAR FORMULÁRIO - POST
// =============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../config/conexao.php';

    $conexao = new conexao();
    $pdo = $conexao->conectar();

    $controller = new AgendamentoController($pdo);

    $acao = $_POST['acao'] ?? 'criar';

    // =========================================================
    // EXCLUIR AGENDAMENTO
    // =========================================================

    if ($acao === 'excluir') {

        $id = $_POST['id'] ?? '';

        $resultado = $controller->excluir($id);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($resultado);

        exit;
    }

    // =========================================================
    // CRIAR AGENDAMENTO
    // =========================================================

    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';

    $resultado = $controller->criar(
        $nome,
        $telefone,
        $data,
        $hora
    );

    // Guarda a mensagem na sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['agendamento_mensagem'] = $resultado['mensagem'];
    $_SESSION['agendamento_sucesso'] = $resultado['sucesso'];

    // Mantém o fluxo atual do seu formulário
    header('Location: ../index.php');

    exit;
}
