<?php
namespace extas\interfaces\jira\routes;

use extas\interfaces\IItem;
use extas\interfaces\jira\IJiraRoute;
use extas\interfaces\jira\IMockServer;
use Psr\Http\Message\RequestInterface;

/**
 * Interface IRouteDispatcher
 *
 * @package extas\interfaces\jira\routes
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IRouteDispatcher extends IItem
{
    public const SUBJECT = 'extas.jira.mock.route.dispatcher';

    public const FIELD__PARSED = 'parsed';
    public const FIELD__ROUTE = 'route';

    /**
     * @param IMockServer $server
     * @param RequestInterface $request
     * @return string
     */
    public function __invoke(IMockServer $server, RequestInterface $request): string;

    /**
     * @return array
     */
    public function getParsed(): array;

    /**
     * @return IJiraRoute
     */
    public function getRoute(): IJiraRoute;
}