<?php
namespace extas\components\plugins;

use extas\components\jira\JiraRoute;
use extas\interfaces\jira\IJiraRouteRepository;

/**
 * Class PluginInstallJiraRoute
 *
 * @package extas\components\plugins
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginInstallJiraRoute extends PluginInstallDefault
{
    protected string $selfSection = 'jira_mock_routes';
    protected string $selfName = 'jira mock route';
    protected string $selfRepositoryClass = IJiraRouteRepository::class;
    protected string $selfUID = JiraRoute::FIELD__NAME;
    protected string $selfItemClass = JiraRoute::class;
}
