## Requisitos

* PHP 8.2 ou superior
* Composer
* Node.js 20 ou superior
* GIT

## Como rodar o projeto baixado

Duplicar o arquivo ".env.example" e renomear para ".env".<br>
Alterar no arquivo .env as credenciais do banco de dados<br>
Para a funcionalidade recuperar senha funcionar, necessário alterar as credenciais do servidor de envio de e-mail no arquivo .env.<br>

Instalar as dependências do PHP
```
composer install
```

Instalar as dependências do Node.js
```
npm install
```

Gerar a chave
```
php artisan key:generate
```

Executar as migration
```
php artisan migrate
```

Executar as seed
```
php artisan db:seed
```

Iniciar o projeto criado com Laravel
```
php artisan serve
```

Executar as bibliotecas Node.js
```
npm run dev
```

Acessar o conteúdo padrão do Laravel
```
http://127.0.0.1:8000
```

## Sequencia para criar o projeto
Criar o projeto com Laravel
```
composer create-project laravel/laravel .
```

Iniciar o projeto criado com Laravel
```
php artisan serve
```

Acessar o conteúdo padrão do Laravel
```
http://127.0.0.1:8000
```

Criar a migration
```
php artisan make:migration create_name_table
```

Executar as migration
```
php artisan migrate
```

Criar Controller
```
php artisan make:controller NomeController
```
```
php artisan make:controller CourseController
```

Criar View
```
php artisan make:view nome
```
```
php artisan make:view courses/show
```

Criar Models
```
php artisan make:model NomeDaModel
```
```
php artisan make:model Course
```

Criar Seed
```
php artisan make:seeder NomeSeeder
```
```
php artisan make:seeder CourseSeeder
```

Executar as seed
```
php artisan db:seed
```

Rollback (reverter) a migração mais recente
```
php artisan migrate:rollback
```

Criar um arquivo de Request com validações
```
php artisan make:request NomeDoRequest
```
```
php artisan make:request CourseRequest
```

Criar componente
```
php artisan make:component nome --view
```
```
php artisan make:component alert --view
```

Instalar o pacote de auditoria do Laravel
```
composer require owen-it/laravel-auditing
```

Publicar a configuração e as migration para auditoria
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="config"
```
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
```

Limpar cache de configuração
```
php artisan config:clear
```

Traduzir para português [Módulo de linguagem pt-BR](https://github.com/lucascudo/laravel-pt-BR-localization).

Instalar as dependências do Node.js
```
npm install
```

Instalar o framework Bootstrap
```
npm i --save bootstrap @popperjs/core
```

Executar as bibliotecas Node.js
```
npm run dev
```

Instalar a biblioteca de ícones
```
npm i --save @fortawesome/fontawesome-free
```

Configurar e-mail recuperar senha
```
php artisan vendor:publish --tag=laravel-mail
```

Instalar a dependência de permissão
```
composer require spatie/laravel-permission
```

Criar as migrations
```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Limpar o cache de configuração
```
php artisan config:clear
```

Executar as migrations
```
php artisan migrate
```

### Será criado 5 tabelas
* funções/roles – Esta tabela armazenará o nome de todas as funções da aplicação.
* permissões/permissions – Esta tabela armazenará o nome de todas as permissões do aplicativo.
* role_has_permissions  – Esta tabela armazenará todas as permissões atribuídas a cada função.
* model_has_roles  – Esta tabela armazenará funções atribuídas a cada modelo.
* model_has_permissions  – Esta tabela armazenará as permissões atribuídas a cada modelo. Por exemplo, um modelo de usuário.

Criar seed de permissão
```
php artisan make:seeder PermissionSeeder
```

Criar seed de papel
```
php artisan make:seeder RoleSeeder
```

## Como usar o GitHub
Baixar os arquivos do Git
```
git clone --branch <branch_name> <repository_url> .
```

Verificar a branch
```
git branch 
```

Baixar as atualizações
```
git pull
```