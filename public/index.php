<?php

// Carrega o autoloader do Composer, responsável por carregar as dependências do projeto
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Src\Config\DB; // Importa a classe DB para gerenciar a conexão com o banco de dados

// Cria a aplicação Slim
$app = AppFactory::create();

// Define o Middleware para gerenciar as rotas e os erros (Me ajudou muito)
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Cria a conexão com o banco de dados
$db = (new DB())->connect();

// Carrega as rotas definidas no arquivo api.php
(require __DIR__ . '/../src/routes/api.php')($app, $db);


$app->run();
