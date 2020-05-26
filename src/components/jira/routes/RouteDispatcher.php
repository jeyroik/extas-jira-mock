<?php
namespace extas\components\jira\routes;

use extas\components\Item;
use extas\interfaces\jira\IJiraRoute;
use extas\interfaces\jira\routes\IRouteDispatcher;

/**
 * Class RouteDispatcher
 *
 * @package extas\components\jira\routes
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class RouteDispatcher extends Item implements IRouteDispatcher
{
    /**
     * @return array
     */
    public function getParsed(): array
    {
        return $this->config[static::FIELD__PARSED] ?? [];
    }

    /**
     * @return IJiraRoute
     */
    public function getRoute(): IJiraRoute
    {
        return $this->config[static::FIELD__ROUTE];
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
