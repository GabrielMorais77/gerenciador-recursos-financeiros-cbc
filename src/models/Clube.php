<?php
namespace Src\Models;

//Classe Clube - Representa a entidade "clube" no sistema, responsável por manipular os dados dos clubes no banco.

class Clube {
    private $conn;
    private $table = 'clubes';

    public $id;
    public $nome;
    public $saldo_disponivel;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Método listar - Recupera todos os registros de clubes da tabela.
    public function listar() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    //Método cadastrar - Insere um novo clube no banco de dados com os atributos nome e saldo_disponivel.
    public function cadastrar() {
        $query = "INSERT INTO {$this->table} (nome, saldo_disponivel) VALUES (:nome, :saldo_disponivel)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':saldo_disponivel', $this->saldo_disponivel);
        return $stmt->execute();
    }
}
