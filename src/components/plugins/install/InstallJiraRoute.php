<?php
namespace extas\components\plugins\install;

use extas\components\jira\JiraRoute;

/**
 * Class InstallJiraRoute
 *
 * @package extas\components\plugins\install
 * @author jeyroik <jeyroik@gmail.com>
 */
class InstallJiraRoute extends InstallSection
{
    protected string $selfSection = 'jira_mock_routes';
    protected string $selfName = 'jira mock route';
    protected string $selfRepositoryClass = 'jiraRouteRepository';
    protected string $selfUID = JiraRoute::FIELD__NAME;
    protected string $selfItemClass = JiraRoute::class;
}
