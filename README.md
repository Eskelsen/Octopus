# Nanoframework
O menor framework PHP

## Doutrina

O Nanoframework é guiado por três princípios:

- Crueza — usar o material original
- Explicitude — tornar o efeito visível
- Planicidade — evitar profundidade estrutural

## Instalação
Clone o repositório
```shell
git clone https://github.com/Eskelsen/Nanoframework.git .
```
Rode o composer
```shell
composer install
```
Crie o arquivo de configurações a partir do modelo
```shell
cp app/config.lock app/config.php
```
---
> ⚠️ Configure as constantes conforme necessário.
---
Rode as migrations
```shell
php app/nano mig up
```
