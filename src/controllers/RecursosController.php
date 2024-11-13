<?php
namespace Src\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Classe Principal do Recuros - Controla os valores dos recuros, incluindo consulta e atualização do saldo

class RecursosController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function consumirRecurso(Request $request, Response $response) {
        // Decodifica o JSON diretamente do corpo da requisição
        $dados = json_decode($request->getBody(), true);

        // Validação dos parâmetros obrigatórios
        if (!isset($dados['clube_id']) || !isset($dados['recurso_id']) || !isset($dados['valor_consumo'])) {
            $response->getBody()->write(json_encode(["message" => "clube_id, recurso_id e valor_consumo são obrigatórios."]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Variáveis para facilitar o entendimento
        $clube_id = $dados['clube_id'];
        $recurso_id = $dados['recurso_id'];
        $valor_consumo = (float) str_replace(',', '.', $dados['valor_consumo']);

        try {
            $this->db->beginTransaction();

            // Consulta o saldo do clube
            $query = "SELECT nome, saldo_disponivel FROM clubes WHERE id = :clube_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':clube_id', $clube_id);
            $stmt->execute();
            $clube = $stmt->fetch();

            if (!$clube) {
                $response->getBody()->write(json_encode(["message" => "Clube não encontrado."]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            // Validação do saldo
            if ($clube['saldo_disponivel'] < $valor_consumo) {
                $response->getBody()->write(json_encode(["message" => "O saldo disponível do clube é insuficiente."]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            // Consulta o saldo do recurso
            $query = "SELECT saldo_disponivel FROM recursos WHERE id = :recurso_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':recurso_id', $recurso_id);
            $stmt->execute();
            $recurso = $stmt->fetch();

            if (!$recurso) {
                $response->getBody()->write(json_encode(["message" => "Recurso não encontrado."]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            // Atualiza o saldo do clube
            $novo_saldo_clube = $clube['saldo_disponivel'] - $valor_consumo;
            $query = "UPDATE clubes SET saldo_disponivel = :novo_saldo WHERE id = :clube_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':novo_saldo', $novo_saldo_clube);
            $stmt->bindParam(':clube_id', $clube_id);
            $stmt->execute();

            // Atualiza o saldo do recurso
            $novo_saldo_recurso = $recurso['saldo_disponivel'] - $valor_consumo;
            $query = "UPDATE recursos SET saldo_disponivel = :novo_saldo WHERE id = :recurso_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':novo_saldo', $novo_saldo_recurso);
            $stmt->bindParam(':recurso_id', $recurso_id);
            $stmt->execute();

            $this->db->commit();

            // Retorna a resposta com os dados atualizados
            $response->getBody()->write(json_encode([
                "clube" => $clube['nome'],
                "saldo_anterior" => number_format($clube['saldo_disponivel'], 2, ',', '.'),
                "saldo_atual" => number_format($novo_saldo_clube, 2, ',', '.')
            ]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

        } catch (\PDOException $e) {
            // Desfaz a transação em caso de erro e informa uma menssagem
            $this->db->rollBack();
            $response->getBody()->write(json_encode(["message" => "Erro ao consumir recurso", "error" => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
