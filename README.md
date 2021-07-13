## Sobre o Projeto

API REST que será responsável por realizar as seguintes funcionalidades:

- Gestão de alertas;
- Gestão de incidentes; e
- Exposição de métricas e saúde da aplicação.

## Instruções para execução da aplicação

``` 
git clone git@github.com:kayobruno/observability-challenge.git
cd observability-challenge
docker-compose up -d

# Observação Importante
# Mesmo após os container estiverem todos "up" é interessante aguardar alguns segundos antes de fazer qualquer request para API,
# pois o container "app" possui um arquivo sh que efetuará algumas configurações iniciais da aplicação, como "composer install" e "Importação dos alertas CSV" 
```
Após a execução dos comandos acima, será criado 4 containers no Docker, onde cada um tem sua responsabilidade. O container "db" será responsável
pelo gerenciamento do banco de dados. O container "app" será responsável pela API REST, que foi desenvolvida utilizando o framework Laravel na última
versão de LTS. O container "nginx" será responsável pelo servidor web. E por último não menos importante temos o container "scripts", este container 
será responsável pela execução do arquivo `metric-generator.sh` onde o mesmo vai executar requisições em uma rota específica da API.

Na raiz da aplicação existe um arquivo chamado `Insomnia-Observability-Challenge.json` que poderá ser importado no Insomnia para ajudar nos testes dos endpoints, caso tenha algum teste manual.

## Implementação em Cloud
Tenho pouca experiência com implementações em Cloud, mas acredito que uma possibilidade viável seria utilizar o Amazon EKS, este serviço é um gerenciador de aplicações conteinerizadas com Kubernetes.
E o Kubernete vai automatizar as operações dos containers da aplicação, além de facilitar o agrupamento dos clusters ele vai ajudar na escalabilidade e performance da aplicação.
