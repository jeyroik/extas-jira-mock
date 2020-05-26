<?php
namespace extas\components\jira\routes;

use extas\components\Replace;
use extas\interfaces\jira\IMockServer;

/**
 * Class RoutePrepared
 *
 * @package extas\components\jira\routes
 * @author jeyroik <jeyroik@gmail.com>
 */
class RoutePrepared extends RouteDispatcher
{
    public const PARAM__PATH = 'path';

    /**
     * @param IMockServer $server
     * @return string
     */
    public function __invoke(IMockServer $server): string
    {
        $route = $this->getRoute();
        $preparedPathSuffix = $route->getParameterValue(static::PARAM__PATH);
        $preparedPath = $server->getBasePath() . $preparedPathSuffix;
        if (is_file($preparedPath)) {
            return Replace::please()->apply(['host' => $server->getHost()])->to(file_get_contents($preparedPath));
        }

        return 'Missed prepared path with suffix "' . $preparedPathSuffix . '"';
    }
}
