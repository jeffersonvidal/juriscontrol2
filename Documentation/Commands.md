# Instalação do Projeto

Fazer deploy do sistema na hospedagem Hostinger - [click](https://medium.com/@anushujan/deploying-a-laravel-11-project-on-hostinger-using-web-hosting-eca710e024f0){:target="_blank"} para ver o tutorial.

Google API - [click](https://github.com/googleapis/google-api-php-client){:target="_blank"} 

Acrescentar ao painel - [Cursos Jurídicos](https://drive.google.com/drive/folders/19PGz71UBy6gcFQfcXVsBoeeZDa1S2oWu){:target="_blank"} 

## Sistemas que inspiram recursos

- EasyJur (dashboard, movimentações, jurisprudencias(wescrapping))
- AdvBox (taskscore, tarefas recorrentes)
- easyvog
- Astrea
- Promad
- MaisJuridico

## Requisitos

- Laravel 12 ou superior
- PHP 8.2 ou superior
- Composer
- NodeJS

### Medidas para gerar arquivos pdf

A4 - 210 x 297(mm), 8,3 x 11,7(pol), 2.480 x 3.508(px)

## Como rodar o projeto baixado

Duplicar o arquivo ".env.example" e renomear para ".env".

## Instalar as dependências do PHP

```
composer install
```

## Instalar as dependências do NodeJS

```
npm install
```

## Instalar Boostrap com Vite

```
npm i --save bootstrap @popperjs/core
```

## Executar Bibliotecas NodeJS

```
npm run dev
```

## Instalar Ícones FontAwesome

```
npm i --save @fortawesome/fontawesome-free
```

## Gerar chave artisan do projeto

```
php artisan key:generate
```

## Gerar arquivo de configuração CORS

```
php artisan config:publish cors
```

## Inciar o projeto criado com Laravel

```
php artisan serve
```

### Acessar conteúdo padrão do Laravel

http://localhost:8000


# Criação de arquivos

Criar migration
```
php artisan make:migration create_users_table
```

Adicionar uma coluna à tabela users
```
php artisan make:migration add_phone_to_users_table --table=users
```

Criar uma migration para remover uma coluna
```
php artisan make:migration remove_phone_from_users_table --table=users
```

Desfazer a última migration
```
php artisan migrate:rollback
```

Criar Model, Controller, Migration, Seeder
```
php artisan make:model Tag -mscr
```

Executar migrate - Criação de banco de dados e tabelas
```
php artisan migrate
```

Executar migrate com seeder - Recriar e popular tabelas no banco de dados
```
php artisan migrate:refresh
php artisan migrate:refresh --seed
```

Popular tabelas do banco de dados com dados predefinidos
```
php artisan db:seed
php artisan db:seed --fresh
```

Criar controller - com todos os métodos
```
php artisan make:controller TagController --resource
```

Criar Request
```
php artisan make:request TagRequest
```

Criar Componente
```
php artisan make:component Toast
```

Criar Inferfaces
```
php artisan make:class Repositories/Interfaces/TagRepositoryInterface --interface
```

Criar Repository
```
php artisan make:class Repositories/Eloquent/TagRepository
```

Criar Service
```
php artisan make:class Services/TagService
```

Criar Job
```
php artisan make:job ProcessTag
```

Limpar Cache
```
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan optimize:clear
php artisan view:clear
```

# Instalação de bibliotecas

## Traduzir o Laravel

Traduzir mensagens do Laravel para PT-BR (https://github.com/lucascudo/laravel-pt-br-localization)
### Instalação
```
php artisan lang:publish
composer require lucascudo/laravel-pt-br-localization --dev
php artisan vendor:publish --tag=laravel-pt-br-localization
```

Configure o Framework para utilizar 'pt_BR' como linguagem padrão
// Altere Linha 85 do arquivo config/app.php para:
'locale' => 'pt_BR'

Recrie o cache das configurações
```
php artisan config:cache
```

Timezone no arquivo .env (insira a linha abaixo logo após APP_DEBUG)
APP_TIMEZONE=America/Sao_Paulo

Corrija o APP_LOCALE para pt_BR
APP_LOCALE=pt_BR

## Laravel Permission

Instalar Laravel Permission (https://spatie.be/docs/laravel-permission/v8/installation-laravel)
```
composer require spatie/laravel-permission
```

Criar migration do laravel permission
```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Limpar o cache
```
php artisan optimize:clear
php artisan config:clear
```

Executar migration
```
php artisan migrate
```

## Auditoria com Laravel Auditing

Instalar Auditoria com Laravel Auditing (https://laravel-auditing.com/)
```
composer require owen-it/laravel-auditing
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="config"
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
php artisan migrate
php artisan optimize:clear
php artisan config:clear
```
