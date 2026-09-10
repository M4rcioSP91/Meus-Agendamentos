<?php
class conexao{
    private $host = 'localhost';
    private $dbname = 'meus_agendamentos';
    private $user = 'root';
    private $pass ='';

    public function conectar ()
    {
        try
        {
            $pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            return $pdo;

        } catch (PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }
}

?>