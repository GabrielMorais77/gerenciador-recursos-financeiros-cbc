<?php

namespace Src\Config;

use PDO;
use Dotenv\Dotenv;
use PDOException;

class DB {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Carrega as configurações do arquivo .env para o sistema
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Define as propriedades do banco de dados, usando valores padrão se não estiverem no .env
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'financeira';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function connect() {
        $this->conn = null;

        try {
            // Tenta estabelecer a conexão com o banco de dados
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );

            // Aqui é onde eu Configurei o PDO para lançar exceções em caso de erro e retornar dados em arrays associativos
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $this->conn;

        } catch (PDOException $e) {
            // Em caso de falha, lida com o erro usando uma função dedicada
            $this->handleError($e);
        }

        return null; // Retorna null se a conexão falhar
    }
    private function handleError(PDOException $e) {
        // Registra o erro em um arquivo de log e mostra uma mensagem amigável ao usuário
        error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
        echo "Erro na conexão com o banco de dados. Tente novamente mais tarde.";

        // Pessoal, aqui eu quis deixar esse opcional por padrão.
        // Caso necessite lançar a exceção novamente se for necessário interromper o fluxo
        throw $e;
    }
}
