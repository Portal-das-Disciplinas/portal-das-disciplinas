# Portal disciplina

## Preparando o ambiente

1. Instalar o xampp
2. Instalar o node
3. Instalar o composer 
4. Instalar o laravel

### Após essas instalações é necessacio acessar o diretorio do projeto no console e utilizar os seguintes comandos

No diretorio do projeto no console utilize os seguintes comandos para instalar as depencendias do laravel 

    composer install
    npm install
    npm run dev


Crie um banco de dados vazio e logo após faça os passos a seguir

No diretorio do projeto no console utilize o seguinte comando para criar uma copia do .env (Aqui onde fica a configuração do banco de dados)
    
    copy .env.example .env

Logo após esse comando configure o arquivo .env para o seu banco de dados

No diretorio do projeto no console utilize o seguinte comando 

    php artisan key:generate

Logo após isso, vamos criar as tabelas do banco. Execute esse comando no prompt

    php artisan migrate

E aqui iremos carregar uma inserção de dados pré definidas em database -> seeders

    php artisan db:seed 

Email para teste no gmail - Fazer login no Mailtrap com esse email! (Ainda em desenvolvimento - OPCIONAL)

    Email: c3970951@gmail.com
    senha: imdufrn123

Configurar as portas stmp no .env dadas no Mailtrap (Ainda em desenvolvimento - OPCIONAL)


