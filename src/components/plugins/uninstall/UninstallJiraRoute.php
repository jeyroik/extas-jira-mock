<?php
namespace extas\components\plugins\uninstall;

use extas\components\jira\JiraRoute;

/**
 * Class UninstallJiraRoute
 *
 * @package extas\components\plugins\uninstall
 * @author jeyroik <jeyroik@gmail.com>
 */
class UninstallJiraRoute extends UninstallSection
{
    protected string $selfSection = 'jira_mock_routes';
    protected string $selfName = 'jira mock route';
    protected string $selfRepositoryClass = 'jiraRouteRepository';
    protected string $selfUID = JiraRoute::FIELD__NAME;
    protected string $selfItemClass = JiraRoute::class;
}
