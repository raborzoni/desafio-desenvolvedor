# Oliveira Trust - Sistema de Uploads

## Visão Geral do Projeto

Este projeto visa fornecer uma solução completa para upload, armazenamento e consulta de arquivos financeiros de forma eficiente e segura. Ele permite que os usuários carreguem arquivos CSV, armazenem esses arquivos em um banco de dados e realizem consultas a partir de parâmetros específicos.

## Estrutura do Projeto

Abaixo está a estrutura dos principais diretórios e componentes do projeto:

*	/app/Http/Controllers:  Contém os controladores principais da aplicação, como UploadController, que gerencia as operações de upload e consulta de arquivos.

*	/database/migrations:  Scripts que criam e mantêm a estrutura do banco de dados, como tabelas e índices.

*	/tests:  Testes de unidade e testes de funcionalidade, garantindo que o sistema esteja funcionando conforme o esperado.

*	/routes:  Arquivos de rota que mapeiam as URLs para os respectivos controladores e métodos.

## Instalação e Configuração
### Requisitos

* PHP ≥ 8.0

* Composer ≥ 2.0

* Laravel ≥ 9.x

* Xampp

### Passos de Instalação

1. Clone o repositório:
~~~bash
git clone https://github.com/seu-usuario/desafio-desenvolvedor.git
cd oliveira-trust
~~~
2. Instale as dependências do projeto:
~~~bash
composer install
npm install && npm run dev
~~~
3. Copie o arquivo .env.example para criar um .env de configuração:
~~~bash
cp .env.example .env
~~~
4. Gere a chave da aplicação .env.example .env
~~~bash
php artisan key:generate
~~~
5. Configure as variáveis de ambiente no arquivo .env, incluindo as informações de conexão ao banco de dados (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
   
6. Execute as migrações para criar as tabelas no banco de dados:
~~~bash
php artisan migrate
~~~ 

## Como Usar?

* Subir o servidor localmente:
~~~bash
php artisan serve
~~~
* Acessar: Abra http://localhost:8000 no navegador para acessar a aplicação.

## Como Rodar os Testes Unitários
Para garantir que todas as funcionalidades estejam funcionando corretamente, rode os testes automatizados com o seguinte comando:
~~~bash
php artisan test
~~~
Isso executa os testes de unidade e testes de integração, verificando o comportamento da API e do banco de dados.

## Principais Endpoints da API
### 1. POST api/upload
Este endpoint é usado para fazer upload de um arquivo CSV.

* Parâmetros: file (​multipart/form-data​)

* Resposta: Status HTTP 200 com mensagem de sucesso ou erro.

### 2. GET /api/history
Recupera o histórico de uploads realizados.

Parâmetros: Nenhum.

Resposta: Uma lista paginada dos arquivos já enviados, incluindo file_name, uploaded_at e outras informações.

### 3. GET /api/search

Permite buscar dados a partir de parâmetros específicos.

* Parâmetros:
* * TckrSymb (opcional) - Símbolo do ticker do ativo.
* * RptDt (opcional) - Data do relatório.

* Resposta: Uma lista paginada com os resultados que correspondem aos critérios especificados.

## Documentação da API

A documentação completa da API pode ser acessada através do Swagger, disponível no link:
&nbsp;
[Documentação Swagger](http://localhost:8000/api/documentation#/Uploads)

## Licenciamento e Informações Legais
Este projeto é licenciado sob a licença MIT.
