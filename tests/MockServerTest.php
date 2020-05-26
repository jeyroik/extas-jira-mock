<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\extensions\TSnuffExtensions;
use extas\components\jira\JiraRoute;
use extas\components\jira\JiraRouteRepository;
use extas\components\jira\MockServer;
use extas\components\jira\routes\RouteLogJsonRequest;
use extas\components\jira\routes\RoutePrepared;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\samples\parameters\ISampleParameter;
use PHPUnit\Framework\TestCase;

/**
 * Class MockServerTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class MockServerTest extends TestCase
{
    use TSnuffExtensions;

    protected IRepository $routeRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->routeRepo = new JiraRouteRepository();
        $this->addReposForExt([
            'jiraRouteRepository' => JiraRouteRepository::class
        ]);
    }

    protected function tearDown(): void
    {
        $this->routeRepo->delete([JiraRoute::FIELD__NAME => '/test']);
        $this->deleteSnuffExtensions();
    }

    public function testRoutePrepared()
    {
        $this->routeRepo->create(new JiraRoute([
            JiraRoute::FIELD__NAME => '/test',
            JiraRoute::FIELD__CLASS => RoutePrepared::class,
            JiraRoute::FIELD__PARAMETERS => [
                RoutePrepared::PARAM__PATH => [
                    ISampleParameter::FIELD__NAME => RoutePrepared::PARAM__PATH,
                    ISampleParameter::FIELD__VALUE => '/prepared.json'
                ]
            ]
        ]));

        $server = new MockServer([
            MockServer::FIELD__HOST => 'test',
            MockServer::FIELD__BASE_PATH => getcwd() . '/tests'
        ]);

        $_REQUEST['REQUEST_URI'] = 'http://localhost/test';

        ob_start();
        $server->run();
        $response = ob_get_contents();
        ob_end_flush();

        $this->assertEquals('{"test": "is ok"}', $response, 'Response mismatched');
    }

    public function testRouteLogJsonRequest()
    {
        $this->routeRepo->create(new JiraRoute([
            JiraRoute::FIELD__NAME => '/test',
            JiraRoute::FIELD__CLASS => RouteLogJsonRequest::class,
            JiraRoute::FIELD__PARAMETERS => [
                RouteLogJsonRequest::PARAM__PATH => [
                    ISampleParameter::FIELD__NAME => RouteLogJsonRequest::PARAM__PATH,
                    ISampleParameter::FIELD__VALUE => '/log.test.json'
                ]
            ]
        ]));

        $server = new MockServer([
            MockServer::FIELD__HOST => 'test',
            MockServer::FIELD__BASE_PATH => getcwd() . '/tests'
        ]);

        $_REQUEST['REQUEST_URI'] = 'http://localhost/test';

        $server->run();

        $this->assertTrue(file_exists(getcwd() . '/tests/log.test.json'), 'Missed log file');
        $this->assertTrue(
            (bool) strpos(file_get_contents(getcwd() . '/tests/log.test.json'), '{"test":"is ok"}'),
            'Log contents mismatched'
        );
        unlink(getcwd() . '/tests/log.test.json');
    }
}
