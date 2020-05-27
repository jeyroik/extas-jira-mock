<?php
namespace extas\components\jira\routes;

use extas\interfaces\jira\IMockServer;
use Psr\Http\Message\RequestInterface;

/**
 * Class RouteLogJsonRequest
 *
 * @package extas\components\jira\routes
 * @author jeyroik <jeyroik@gmail.com>
 */
class RouteLogJsonRequest extends RouteDispatcher
{
    public const PARAM__PATH = 'path';

    /**
     * @param IMockServer $server
     * @param RequestInterface $request
     * @return string
     */
    public function __invoke(IMockServer $server, RequestInterface $request): string
    {
        $route = $this->getRoute();
        $logPathSuffix = $route->getParameterValue(static::PARAM__PATH);

        $logPath = $server->getBasePath() . $logPathSuffix;
        $json = (string) $request->getBody();

        file_put_contents(
            $logPath,
            '[' . date('Y-m-d H:i:s') . ']' . $json . PHP_EOL,
            FILE_APPEND
        );

        return '';
    }
}
