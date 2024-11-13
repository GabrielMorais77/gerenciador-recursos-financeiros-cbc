# 📊 Gerenciamento de Recursos Financeiros - CBC

Este projeto é uma **API RESTful** para gerenciamento de recursos financeiros entre clubes. A aplicação permite cadastrar clubes, monitorar saldos e registrar consumos de recursos, como passagens e hospedagens. Cada operação reduz o saldo do clube e do recurso associado, mantendo a integridade dos saldos.

## 🛠️ Tecnologias Utilizadas

- <img src="https://raw.githubusercontent.com/tandpfun/skill-icons/65dea6c4eaca7da319e552c09f4cf5a9a8dab2c8/icons/PHP-Dark.svg" alt="PHP" style="vertical-align: middle; width: 28px; height: 28px; border-radius: 50%;"> **8.3**: Foi utilizada a versão 8.3 que oferece maior desempenho e recursos modernos, como tipagem aprimorada e recursos de manipulação de dados.
- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAY1BMVEWbu3mcu3qcvHqVt3CRtGt+p1KbuX7C0rLO28GXtniGrVzr8Ob///++0K1rmzKXuXT//v6zyZ/I17ro7uKOsmjd5tWmwI3T38iKsGLy9e6Fq16txJaPsm2gu4XD1LRwnjzY4s624ziOAAAA9UlEQVR4AZXPB3bDIBAEUBZ1GFugXhPd/5RZ7EfUU0aF8qniH6FbkSIIAyGvLYqTNMkUcU6oNFwez0hF+Z4oMjDGwBbsZba3KgUj6tQNadqOdhsCKWPjDP0WKVcD8CyBkpG13mCe6R4GDDAHJFK6f+11RhnmSo/Te70GO6TqMStts3fvh8YWZVfayKNB1oNjA+GxHuDRwI5jk3XSH3UGeuXRAP1n+H0RCqIoEh5ZtRo8shLJPSrGTXYYjRWdEYBD1V3MxDjVcLgxj6iVHF4oL3Dp6BonAG1FEYDyiLKzSztICptlmfbkNKgqyUVeVeIiRL74U74ATHAVSsL3f5IAAAAASUVORK5CYII=" alt="Imagem" style="vertical-align: middle; width: 28px; height: 28px; border-radius: 50%;"> **Slim Framework v4**:  Micro-Framework bastante leve e prático para desenvolvimento de APIs RESTful com suporte a rotas dinâmicas e middleware
- <img src="https://raw.githubusercontent.com/tandpfun/skill-icons/65dea6c4eaca7da319e552c09f4cf5a9a8dab2c8/icons/MySQL-Dark.svg" alt="MySQL" style="vertical-align: middle; width: 28px; height: 28px; border-radius: 50%;">**MySQL 8.x**:  Banco de dados relacional, ideal para manipulações transacionais, assegurando integridade nos registros de saldo e consumo de recursos.

## 📦📚 Dependências e Bibliotecass
- **Composer**: Gerenciador de pacotes PHP usado para instalar e manter as dependências do projeto.
- **Dotenv**: Permite carregar variáveis de ambiente para proteger credenciais sensíveis.
- **PDO (PHP Data Objects)**: Extensão de PHP para acesso seguro ao banco de dados com suporte a transações, utilizada para prevenir SQL Injection e gerenciar as conexões.


## 📂 Estrutura do Projeto

```bash
gerenciamento-recursos-financeiros-cbc/
│
├── public/
│   └── index.php                 # Ponto de entrada da aplicação e configuração de rotas.
│
├── src/
│   ├── Config/
│   │   └── DB.php                # Classe para conexão segura com o banco de dados.
│   │
│   ├── Controllers/
│   │   ├── ClubeController.php   # Controlador com operações para clubes.
│   │   └── RecursosController.php # Controlador com operações para recursos.
│   │
│   ├── Models/
│   │   ├── Clube.php             # Modelo de dados para os clubes.
│   │   └── Recurso.php           # Modelo de dados para os recursos.
│   │
│   └── Routes/
│       └── api.php               # Definições das rotas e endpoints da API.
│
├── .env                          # Configurações sensíveis (credenciais de banco).
├── composer.json                 # Arquivo de configuração do Composer.
└── README.md                     # Documentação detalhada do projeto.


```

## 🔗 Endpoints da API

### Clube
- **GET /clubes**: Retorna a lista de todos os clubes com seus respectivos saldos.
- **POST /clubes**: Cadastra um novo clube, recebendo nome e saldo_disponivel.
### Recursos
- **POST /recursos/consumir**: Consome um recurso, debitando o saldo do clube e do recurso correspondente.

## 📜 Regras de Consumo de Recursos
1. **Sem Saldo Negativo**: Um clube não pode consumir um recurso se o saldo ficar negativo.
2. **Desconto Duplo**: O valor é debitado tanto do saldo do clube quanto do saldo disponível do recurso.
3. **Transações Atômicas**: As operações são realizadas em transações para garantir que, em caso de erro, o saldo não seja alterado.

## 🚀 Instalação
### 📌 Pré-requisitos
- **PHP 8.3**
- **Composer**
- **MySQL 8.0**
### 📝 Passo a Passo

### 1. Clone o repositório:
```bash
git clone https://github.com/GabrielMorais77/gerenciador-recursos-financeiros-cbc.git
cd gerenciador-recursos-financeiros-cbc
```
### 2. Instale as dependências:
```bash
composer install
```
### 3. Configuração do Banco de Dados:

- Recomendação (Crie um arquivo .env na raiz do projeto e adicione as configurações)
```bash
DB_HOST=localhost #Por padrao o Host é localhost, se for diferente mude aqui tambem
DB_NAME=financeira #Foi o nome do banco de dados que escolhi para API
DB_USER=root #coloque seu usuario e lembra-se de da permissão
DB_PASS=sua_senha #informe sua senha do banco de dados
```
### 4. Crie as tabelas do banco de dados:
```bash
CREATE DATABASE financeira;
USE financeira;

CREATE TABLE clubes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    saldo_disponivel DECIMAL(10, 2) NOT NULL
);

CREATE TABLE recursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recurso VARCHAR(255) NOT NULL,
    saldo_disponivel DECIMAL(10, 2) NOT NULL
);
```
### 5. Inicie o servidor e teste a API:
```bash
php -S localhost:8000 -t public
```

## 📤 Testando com Postman
- Esta API foi testada utilizando o Postman. Se desejar realizar testes também, siga os passos abaixo:

### Listar Clubes
- **Método**: GET
- **URL**: http://localhost:8000/clubes

### Cadastrar Clube
- **Método:** POST
- **URL:** http://localhost:8000/clubes
- **Body (JSON):**
```bash
{
  "nome": "Clube A",
  "saldo_disponivel": 2000.00
}
```
### Consumir Recurso
- **Método:** POST
- **URL:** http://localhost:8000/recursos/consumir
- **Body (JSON)**
```bash
{
  "clube_id": "1",
  "recurso_id": "1",
  "valor_consumo": 500
}
```
## 🛑 Possíveis Erros e Soluções
**1. Erro 500 - Internal Server Error:**
- Verifique se as tabelas foram criadas corretamente no banco de dados.
Confirme se o .env está configurado corretamente e se as credenciais estão corretas.

**2. Erro de Conexão:**
- Certifique-se de que o MySQL está rodando.
Verifique se as informações de DB_HOST, DB_NAME, DB_USER, e DB_PASS estão corretas no arquivo .env.

**3. Erro 400 - Bad Request:**
- Certifique-se de que todos os parâmetros obrigatórios foram passados no body da requisição.
Verifique o tipo dos dados enviados (por exemplo, certifique-se de enviar valor_consumo como um número).

**4. Saldo Insuficiente:**
- Verifique o saldo do clube para assegurar que o valor do consumo solicitado não o levará a um saldo negativo.

**Feito com muito carinho por Gabriel de Morais Rodrigues**