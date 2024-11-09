# Min Framework PHP

> Este projeto está sendo desenvolvido visando ter uma estrutura pronta para trabalhar com projetos em PHP puro, utilizando orientação a objetos. Estou tendo como inspiração para criar algumas das funcionalidades da aplicação o Laravel.

# Como rodar o projecto

Se você for fazer contribuições, faça um fork do projeto, crie sua branch e envie um PR. Certifique-se de ter o docker instalado em sua máquina.

### Comandos básicos:

Clone o projeto para sua máquina

```
    git clone git@github.com:caiquebispo/min-framework.git
```
Navegue até o local do projeto e execute o seguinte comando para rodar a aplicação.

```
    docker compose up 
```

### Outros comandos:

Ver a rede que está rodando a aplicação.

```
    docker inspect network bridge
```
Executar comando dentro dos containers

```
    docker exec -it nome_do_container
```
Executar comandos composer

```
    docker exec -it min-framework-php-fpm-1 bash 
```

## Informação da aplicação

- Host: localhost
- Port: 8002
- Mysql Host: 172.17.0.1
- Mysql User: root
- Mysql Password: password
- Mysql Table: project


