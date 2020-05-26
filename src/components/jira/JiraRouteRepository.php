<?php
namespace extas\components\jira;

use extas\components\repositories\Repository;
use extas\interfaces\jira\IJiraRouteRepository;

/**
 * Class JiraRouteRepository
 *
 * @package extas\components\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class JiraRouteRepository extends Repository implements IJiraRouteRepository
{
    protected string $name = 'jira_routes';
    protected string $scope = 'extas';
    protected string $pk = JiraRoute::FIELD__NAME;
    protected string $itemClass = JiraRoute::class;
}
