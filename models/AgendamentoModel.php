<?php

class Agendamento
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    // =========================================================
    // BUSCAR HORÁRIOS OCUPADOS
    // =========================================================

    public function horariosOcupados($data)
    {
        $sql = "SELECT DATE_FORMAT(hora_agendamento, '%H:%i') AS hora_agendamento
                FROM tb_agendamentos
                WHERE data_agendamento = :data";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':data', $data);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    // =========================================================
    // VERIFICAR SE O HORÁRIO JÁ ESTÁ OCUPADO
    // =========================================================

    public function horarioOcupado($data, $hora)
    {
        $sql = "SELECT id
                FROM tb_agendamentos
                WHERE data_agendamento = :data
                AND TIME_FORMAT(hora_agendamento, '%H:%i') = :hora
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':hora', $hora);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // CRIAR AGENDAMENTO
    // =========================================================

    public function criar($nome, $telefone, $data, $hora)
    {
        // Verifica novamente se o horário está ocupado

        if ($this->horarioOcupado($data, $hora)) {

            return false;

        }


        $sql = "INSERT INTO tb_agendamentos
                (
                    nome_cliente,
                    telefone,
                    data_agendamento,
                    hora_agendamento
                )
                VALUES
                (
                    :nome,
                    :telefone,
                    :data,
                    :hora
                )";


        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':hora', $hora);


        return $stmt->execute();
    }

    // =========================================================
    // BUSCAR AGENDAMENTOS POR DATA
    // =========================================================

    public function listarPorData($data)
    {
        $sql = "SELECT
                    id,
                    nome_cliente,
                    telefone,
                    data_agendamento,
                    TIME_FORMAT(hora_agendamento, '%H:%i') AS hora_agendamento
                FROM tb_agendamentos
                WHERE data_agendamento = :data
                AND data_agendamento >= CURDATE()
                ORDER BY hora_agendamento ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':data', $data);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

