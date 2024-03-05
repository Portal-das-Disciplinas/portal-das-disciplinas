# Portal das Disciplina

## Passo 1 - Preparando o ambiente

Para rodar o projeto é necessário instalar os requisitos abaixo:

1. Instalar o xampp (Versão 7.3.33)
2. Instalar o node
3. Instalar o composer
4. Instalar o laravel

## Passo 2 - Depêndencias Laravel

### Após essas instalações é necessário acessar o diretório do projeto no console e utilizar os seguintes comandos:

    composer install
    npm install

## Passo 3 - Configurando banco de dados

1. **Acesse o Admin do MySQL no Xampp e crie um banco de dados vazio**
2. **No diretorio do projeto no console utilize o seguinte comando para criar uma copia do .env (Aqui onde fica a configuração do banco de dados)**

    copy .env.example .env

3. **Logo após esse comando configure o arquivo .env para o seu banco de dados. É nescessário também inserir informações de acesso à API Sistemas para exibir os índices de desempenho das turmas.**

## Rode os comandos abaixo no diretório do projeto

    php artisan key:generate

Logo após isso, vamos criar as tabelas do banco. Execute esse comando no prompt

    php artisan migrate

E aqui iremos carregar uma inserção de dados pré definidas em database -> seeders

    php artisan db:seed

## Passo 4 - Rodando o projeto

**Execute o comando abaixo para rodar o projeto localmente**

    php artisan serve

## Extras

Email para teste no gmail - Fazer login no Mailtrap com esse email! (Ainda em desenvolvimento - OPCIONAL)

    Email: c3970951@gmail.com
    senha: imdufrn123

Configurar as portas stmp no .env dadas no Mailtrap (Ainda em desenvolvimento - OPCIONAL)
