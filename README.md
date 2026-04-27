# Octopus
Sistema de gerenciamento de esforço baseado em PHP simples.

Este projeto nasceu como fork do Nanoframework, mas agora segue como produto próprio voltado ao registro e acompanhamento de esforço.

## Doutrina

O Octopus herda três princípios da base original:

- Crueza — usar o material original
- Explicitude — tornar o efeito visível
- Planicidade — evitar profundidade estrutural

## Instalação
Clone o repositório
```shell
git clone <repo-do-octopus> .
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
php app/octopus mig up
```
