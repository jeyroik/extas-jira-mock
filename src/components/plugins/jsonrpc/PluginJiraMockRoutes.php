<?php
namespace extas\components\plugins\jsonrpc;

use extas\components\jira\MockServer;
use extas\components\Plugins;
use extas\components\plugins\Plugin;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

/**
 * Class PluginJiraMockRoutes
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginJiraMockRoutes extends Plugin
{
    /**
     * @param App $app
     */
    public function __invoke(App &$app): void
    {
        $app->any(
            '/',
            function (Request $request, Response $response, array $args) {
                $uri = $request->getUri();
                $mock = new MockServer([
                    MockServer::FIELD__HOST => getenv('EXTAS__JIRA_MOCK__HOST') ?: 'http://localhost',
                    MockServer::FIELD__BASE_PATH => getenv('EXTAS__JIRA_MOCK__BASE_PATH') ?: getcwd()
                ]);

                return $mock->run($request, $response);
            }
        );
    }
}
