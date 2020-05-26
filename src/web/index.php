<?php
require(__DIR__ . '/../../vendor/autoload.php');
define('APP__ROOT', getenv('EXTAS__BASE_PATH') ?: __DIR__ . '/../..');

use extas\components\jira\MockServer;

if (is_file(APP__ROOT . '/.env')) {
    $dotenv = \Dotenv\Dotenv::create(APP__ROOT . '/');
    $dotenv->load();
}

$server = new MockServer( [
    MockServer::FIELD__HOST => getenv('EXTAS__JIRA_MOCK__HOST') ?: 'http://localhost',
    MockServer::FIELD__BASE_PATH => getenv('EXTAS__JIRA_MOCK__BASE_PATH') ?: __DIR__ . '/../..'
]);
$server->run();