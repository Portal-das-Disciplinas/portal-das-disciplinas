# Portal disciplina

## Passo 1 - Preparando o ambiente

Para rodar o projeto é necessário instalar os requisitos abaixo:

1. Instalar o xampp (Versão 7.3)
2. Instalar o node
3. Instalar o composer
4. Instalar o laravel

## Passo 2 - Depêndencias Laravel

### Após essas instalações é necessacio acessar o diretorio do projeto no console e utilizar os seguintes comandos:

    composer install
    npm install
    npm run dev

## Passo 3 - Configurando banco de dados

1. **Acesse o Admin do MySQL no Xampp e crie um banco de dados vazio**
2. **No diretorio do projeto no console utilize o seguinte comando para criar uma copia do .env (Aqui onde fica a configuração do banco de dados)**

    copy .env.example .env

3. **Logo após esse comando configure o arquivo .env para o seu banco de dados**

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
