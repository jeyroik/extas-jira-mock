![tests](https://github.com/jeyroik/extas-jira-mock/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-jira-mock/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 
<a href="https://codeclimate.com/github/jeyroik/extas-jira-mock/maintainability"><img src="https://api.codeclimate.com/v1/badges/984793c4ec4201f5824e/maintainability" /></a>

# Описание

Мок-сервер Jira. Эмулирует работу Jira.

# Использование

## Запускаем сервер

`extas-jira-mock# php -S 0.0.0.0:8080 -t src/web`

## Шлём запросы

`# curl localhost:8080/rest/api/2/issue`