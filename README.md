# Planejamento Acadêmico

Ferramenta para o apoio no processo de alocação de salas de aulas da Universidade Federal da Bahia.

## Requisitos
* PHP >= 7.1.3
* Extensão PHP OpenSSL
* Extensão PHP PDO
* Extensão PHP Mbstring
* Extensão PHP Tokenizer
* Extensão PHP XML
* Extensão PHP Ctype
* Extensão PHP JSON
* Composer
* MySQL

## Instalação
Clonar o repositorio ou baixar o projeto .zip
```
git clone https://github.com/diegoscunha/planejamento.git
```
Instalar as dependências com o composer
```
composer install
```
Criar um banco de dados e configurar o arquivo .env com as informações de acesso ao Banco de dados.
Rodar as migrações para criar as tabelas do Banco de dados.
```
php artisan migrate
```
Acessar o link locahost:8000 no navegador de sua preferência.
Utilizar as credencias de acesso
admin@admin.com.br
123456
