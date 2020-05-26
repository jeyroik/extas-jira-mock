<?php
namespace extas\components\jira\routes;

use extas\interfaces\jira\IMockServer;

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
     * @return string
     */
    public function __invoke(IMockServer $server): string
    {
        $route = $this->getRoute();
        $logPathSuffix = $route->getParameterValue(static::PARAM__PATH);

        $logPath = $server->getBasePath() . $logPathSuffix;
        $json = file_get_contents('php://input');

        file_put_contents(
            $logPath,
            '[' . date('Y-m-d H:i:s') . ']' . $json . PHP_EOL,
            FILE_APPEND
        );

        return '';
    }
}
