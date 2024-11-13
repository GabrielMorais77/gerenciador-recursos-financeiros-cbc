<?php
use Src\Controllers\ClubeController;
use Src\Controllers\RecursosController;

return function ($app, $db) {

    // Criar uma instância do ClubeController e passar a conexão com o banco de dados
    $clubeController = new ClubeController($db);

    // Define a rota GET para listar os clubes e POST para cadastrar um novo clube
    $app->get('/clubes', [$clubeController, 'listar']);
    $app->post('/clubes', [$clubeController, 'cadastrar']);

    // Define a rota POST para consumir recursos
    $app->post('/recursos/consumir', function ($request, $response) use ($db) {
        $controller = new RecursosController($db);
        return $controller->consumirRecurso($request, $response);
    });
};
