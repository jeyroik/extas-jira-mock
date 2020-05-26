<?php
namespace extas\components\jira;

use extas\components\Item;
use extas\components\samples\parameters\THasSampleParameters;
use extas\components\TDispatcherWrapper;
use extas\interfaces\jira\IJiraRoute;

/**
 * Class JiraRoute
 *
 * @package extas\components\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class JiraRoute extends Item implements IJiraRoute
{
    use TDispatcherWrapper;
    use THasSampleParameters;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
