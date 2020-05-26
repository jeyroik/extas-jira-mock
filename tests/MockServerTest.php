<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\extensions\TSnuffExtensions;
use extas\components\jira\JiraRoute;
use extas\components\jira\JiraRouteRepository;
use extas\components\jira\routes\RouteLogJsonRequest;
use extas\components\jira\routes\RoutePrepared;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\samples\parameters\ISampleParameter;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

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

        $client = new Client(['http_errors' => false]);

        $response = $client->request("GET", "http://0.0.0.0:8080/test");
        $this->assertEquals('{"test":"is ok"}', (string) $response->getBody(), 'Response mismatched');
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

        $client = new Client(['http_errors' => false]);

        $client->request("POST", "http://0.0.0.0:8080/test", [
            'json' => [
                'test' => 'is ok'
            ]
        ]);
        $this->assertTrue(file_exists(getcwd() . '/tests/log.test.json'), 'Missed log file');
        $this->assertTrue(
            (bool) strpos(file_get_contents(getcwd() . '/tests/log.test.json'), '{"test":"is ok"}'),
            'Log contents mismatched'
        );
        unlink(getcwd() . '/tests/log.test.json');
    }
}
