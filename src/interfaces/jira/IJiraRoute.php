<?php
namespace extas\interfaces\jira;

use extas\interfaces\IDispatcherWrapper;
use extas\interfaces\IItem;
use extas\interfaces\samples\parameters\IHasSampleParameters;

/**
 * Interface IJiraRoute
 *
 * @package extas\interfaces\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IJiraRoute extends IItem, IDispatcherWrapper, IHasSampleParameters
{
    public const SUBJECT = 'extas.jira.route';
}
