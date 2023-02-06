# Checklist Fácil - Cakes

## O Desafio

Realizar o desenvolvimento da proposta a seguir utilizando ao máximo as funcionalidades
do framework Laravel em sua versão mais recente.

## Proposta

- Criar um CRUD de rotas de API para o cadastro de bolos.
- Os bolos deverão ter *Nome*, *Peso (em gramas)*, *Valor*, *Quantidade disponível* e uma lista de e-mail de interessados
- Após o cadastro de e-mails interessados, caso haja bolo disponível, o sistema deve enviar um e-mail para os interessados sobre a disponibilidade do bolo

## Casos a avalia

- Pode ocorrer de *50.000* clientes se cadastrarem e o processo de envio de emails não deve ser algo impeditivo.

## Como foi desenvolvido

Desenvolvi utilizando o *laravel sail*, para ganho de ambiente com docker

## Comandos

Para iniciar a aplicação, execute:

```shell
./vendor/bin/sail up
```

## Acesso aos containers

Container onde fica localizado o *sail*, para comandos do *php artisan*

```shell
docker exec -it example-app-laravel.test-1 /bin/sh
````

### Comandos para inicializar a aplicação *laravel sail*

```shell
php artisan migrate && php artisan db:seed
```

Caso queira registros já definidos

```shell
php artisan db:seed
```

### Habilitando o Redis

Instale o *predis/predis*

```shell
composer require predis/predis
```

### Comandos para controle das filas

Utilizando o Horizon

```shell
composer require laravel/horizon

php artisan horizon:install && php artisan horizon
```

ou

```shell
php artisan queue:work
```
