<?php
namespace extas\components\jira;

use extas\components\Item;
use extas\interfaces\jira\IJiraRoute;
use extas\interfaces\jira\IMockServer;
use extas\interfaces\stages\IStageJiraMockResponse;

/**
 * Class MockServer
 *
 * @method jiraRouteRepository(): IRepository
 *
 * @package extas\components\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class MockServer extends Item implements IMockServer
{
    /**
     * Operate request
     */
    public function run(): void
    {
        $route = $_SERVER['REQUEST_URI'];
        $parsed = parse_url($route);

        header('Content-Type: application/json');

        /**
         * @var IJiraRoute $route
         */
        $route = $this->jiraRouteRepository()->one([IJiraRoute::FIELD__NAME => $parsed['path']]);

        if ($route) {
            $dispatcher = $route->buildClassWithParameters([
                'parsed' => $parsed,
                'route' => $route
            ]);
            $result = $dispatcher($this);
            foreach ($this->getPluginsByStage(IStageJiraMockResponse::NAME) as $plugin) {
                /**
                 * @var IStageJiraMockResponse $plugin
                 */
                $plugin($result);
            }
            echo $result;
        } else {
            echo json_encode(['error' => 'Unknown path "' . $parsed['path'] . '"']);
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->config[static::FIELD__HOST] ?? '';
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->config[static::FIELD__BASE_PATH] ?? '';
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
