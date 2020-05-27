<?php
namespace extas\components\jira;

use extas\components\Item;
use extas\interfaces\jira\IJiraRoute;
use extas\interfaces\jira\IMockServer;
use extas\interfaces\stages\IStageJiraMockResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getUri();
        $parsed = parse_url($route);

        $response->withHeader('Content-Type', 'application/json');

        /**
         * @var IJiraRoute $route
         */
        $route = $this->jiraRouteRepository()->one([IJiraRoute::FIELD__NAME => $parsed['path']]);

        if ($route) {
            $dispatcher = $route->buildClassWithParameters([
                'parsed' => $parsed,
                'route' => $route
            ]);
            $result = $dispatcher($this, $request);
            foreach ($this->getPluginsByStage(IStageJiraMockResponse::NAME) as $plugin) {
                /**
                 * @var IStageJiraMockResponse $plugin
                 */
                $plugin($request, $result);
            }
            $response->getBody()->write($result);
        } else {
            $response->getBody()->write(json_encode(['error' => 'Unknown path "' . $parsed['path'] . '"']));
        }

        return $response;
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
