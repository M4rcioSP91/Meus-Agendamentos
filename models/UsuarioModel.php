
<?php

class UsuarioModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Busca o usuário pelo ID
    public function buscarPorId(int $id)
    {
        $sql = "SELECT id, senha
                FROM tb_usuarios
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza a senha do usuário
    public function alterarSenha(int $id, string $novaSenhaHash)
    {
        $sql = "UPDATE tb_usuarios
                SET senha = :senha
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':senha' => $novaSenhaHash,
            ':id' => $id
        ]);
    }
}
