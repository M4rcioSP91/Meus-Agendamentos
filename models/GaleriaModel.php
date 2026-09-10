<?php

class GaleriaModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista imagens
    public function listar()
    {
        $sql = "SELECT id, imagem, criado_em
                FROM tb_galeria
                ORDER BY criado_em DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca imagem pelo ID
    public function buscarPorId($id)
    {
        $sql = "SELECT id, imagem, criado_em
                FROM tb_galeria
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Adiciona imagem
    public function adicionar($imagem)
    {
        $sql = "INSERT INTO tb_galeria (imagem)
                VALUES (:imagem)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':imagem' => $imagem
        ]);
    }

    // Exclui imagem
    public function excluir($id)
    {
        $sql = "DELETE FROM tb_galeria
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}