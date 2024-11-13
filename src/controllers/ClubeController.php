<?php

namespace Src\Controllers;

use Src\Models\Clube;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Classe Principal - Controla as ações dos clubes, incluindo listagem e cadastro, manipulando dados de requisição e resposta.

class ClubeController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para listar os clubes
    public function listar(Request $request, Response $response) {
        $clube = new Clube($this->db);
        $result = $clube->listar();
        $clubes = $result->fetchAll(); // Pega todos os clubes

        // Retorna os clubes em formato JSON
        $response->getBody()->write(json_encode($clubes));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    // Método para cadastrar um clube
    public function cadastrar(Request $request, Response $response) {
        // Lê o corpo da requisição e decodifica o JSON
        $dados = json_decode($request->getBody(), true);

        // Verifica se houve erro ao decodificar o JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response->getBody()->write(json_encode([
                "message" => "Erro ao processar JSON",
                "error" => json_last_error_msg()
            ]));
            return $response->withStatus(400);
        }

        // Verifica se os campos obrigatórios estão presentes (nome e saldo)
        if (!isset($dados['nome']) || !isset($dados['saldo_disponivel'])) {
            $response->getBody()->write(json_encode(["message" => "Nome e saldo são obrigatórios."]));
            return $response->withStatus(400);
        }

        // Verifica se os dados não estão vazios
        if (empty($dados['nome']) || empty($dados['saldo_disponivel'])) {
            $response->getBody()->write(json_encode(["message" => "Nome e saldo não podem ser vazios."]));
            return $response->withStatus(400);
        }

        // Aqui e onde cria uma consulta SQL para inserir um novo clube
        $sql = "INSERT INTO clubes (nome, saldo_disponivel) VALUES (:nome, :saldo_disponivel)";
        $stmt = $this->db->prepare($sql);

        // Bind dos parâmetros para evitar SQL Injection
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':saldo_disponivel', $dados['saldo_disponivel']);

        // Tenta inserir o clube no banco
        try {
            $stmt->execute();

            // Se a inserção for bem-sucedida, retorna uma mensagem de sucesso
            $response->getBody()->write(json_encode(["message" => "Clube cadastrado com sucesso"]));
            return $response->withStatus(201);

        } catch (\PDOException $e) {
            // Caso haja erro, retorna uma mensagem de erro
            $response->getBody()->write(json_encode(["message" => "Erro ao cadastrar clube", "error" => $e->getMessage()]));
            return $response->withStatus(500);
        }
    }
}
