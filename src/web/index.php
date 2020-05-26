<?php
require(__DIR__ . '/../../vendor/autoload.php');

use extas\components\jira\MockServer;

$server = new MockServer( [
    MockServer::FIELD__HOST => getenv('EXTAS__JIRA_MOCK__HOST') ?: 'http://localhost',
    MockServer::FIELD__BASE_PATH => getenv('EXTAS__JIRA_MOCK__BASE_PATH') ?: __DIR__ . '/../..'
]);
$server->run();