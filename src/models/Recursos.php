<?php
namespace Src\Models;

// Classe Recurso - Gerencia dados de recursos, como a descrição e o saldo disponível

class Recurso {
    private $conn;
    private $table = 'recursos';

    public $id;
    public $descricao;
    public $saldo_disponivel;

    //Constructor da Classe - Recebe a conexão com o banco de dados

    public function __construct($db) {
        $this->conn = $db;
    }
}
