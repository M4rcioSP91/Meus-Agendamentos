
<?php

require_once __DIR__ . '/../models/AgendamentoModel.php';

class AgendamentoController
{
    private $agendamento;

    // Horários disponíveis
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
    // RETORNAR HORÁRIOS DISPONÍVEIS
    // =========================================================

    public function horariosDisponiveis($data)
    {
        // Verifica se a data é válida
        if (!$this->dataValida($data)) {
            return [];
        }

        // Domingo não possui horários
        if ($this->ehDomingo($data)) {
            return [];
        }

        // Busca os horários já ocupados no banco
        $ocupados = $this->agendamento->horariosOcupados($data);

        // Remove os horários ocupados
        $disponiveis = array_diff($this->horarios, $ocupados);

        return array_values($disponiveis);
    }

    // =========================================================
    // LISTAR AGENDAMENTOS
    // =========================================================

    public function listarPorData($data)
    {
        // Data atual

        $hoje = date('Y-m-d');


        // Se a data estiver vazia, usa hoje

        if (empty($data)) {

            $data = $hoje;

        }


        // Impede visualizar dias anteriores

        if ($data < $hoje) {

            $data = $hoje;

        }


        return $this->agendamento->listarPorData($data);
    }


    // =========================================================
    // CRIAR AGENDAMENTO
    // =========================================================

    public function criar($nome, $telefone, $data, $hora)
    {
        // Nome
        if (empty(trim($nome))) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe seu nome.'
            ];
        }


        // Telefone
        if (empty(trim($telefone))) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe seu telefone.'
            ];
        }


        // Data
        if (!$this->dataValida($data)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Data inválida.'
            ];
        }


        // Não permite data anterior
        if ($data < date('Y-m-d')) {
            return [
                'sucesso' => false,
                'mensagem' => 'Não é possível agendar uma data que já passou.'
            ];
        }


        // Não permite domingo
        if ($this->ehDomingo($data)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Não é possível realizar agendamentos aos domingos.'
            ];
        }


        // Verifica se o horário existe
        if (!in_array($hora, $this->horarios)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Horário inválido.'
            ];
        }


        // Verifica se o horário já foi ocupado
        if ($this->agendamento->horarioOcupado($data, $hora)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Esse horário já foi agendado. Escolha outro horário.'
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
            'mensagem' => 'Não foi possível realizar o agendamento.'
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
        $dataObjeto = DateTime::createFromFormat(
            'Y-m-d',
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

        // 0 = domingo
        return $dataObjeto->format('w') == 0;
    }
}

// ============================================================= 
// BUSCAR HORÁRIOS DISPONÍVEIS 
// ============================================================= 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar_horarios'])) {
    require_once __DIR__ . '/../config/conexao.php'; 

    $conexao = new conexao(); 
    $pdo = $conexao->conectar(); 
    
    $controller = new AgendamentoController($pdo); 
    
    $data = $_GET['data'] ?? ''; 
    
    $horariosDisponiveis = $controller->horariosDisponiveis($data); 
    
    header('Content-Type: application/json'); 
    
    echo json_encode([ 
        'sucesso' => true, 
        'horarios' => $horariosDisponiveis 
    ]); 
}




// =============================================================
// PROCESSAR FORMULÁRIO
// =============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../config/conexao.php';

    $conexao = new conexao();
    $pdo = $conexao->conectar();

    $controller = new AgendamentoController($pdo);


    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';


    // Cria o agendamento
    $resultado = $controller->criar(
        $nome,
        $telefone,
        $data,
        $hora
    );


    // Guarda a mensagem na sessão
    session_start();

    $_SESSION['agendamento_mensagem'] = $resultado['mensagem'];
    $_SESSION['agendamento_sucesso'] = $resultado['sucesso'];


    // Volta para a página de agendamento
    header('Location: ../index.php?pagina=agendamentos');

    exit;
}

