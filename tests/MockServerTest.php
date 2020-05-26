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

        putenv('EXTAS__JIRA_MOCK__HOST=test');
        putenv('EXTAS__JIRA_MOCK__BASE_PATH=' . getcwd() . '/tests');

        $process = new Process('php -S localhost:8080 -t ' . getcwd() . '/src/web');
        $process->start();

        $client = new Client(['http_errors' => false]);

        $response = $client->request("GET", "http://localhost:8080/test");
        $this->assertEquals('{"test":"is ok"}', $response->getStatusCode());

        $process->stop();
    }

    public function testRouteLogJsonRequest()
    {
        $this->routeRepo->create(new JiraRoute([
            JiraRoute::FIELD__NAME => '/test',
            JiraRoute::FIELD__CLASS => RouteLogJsonRequest::class,
            JiraRoute::FIELD__PARAMETERS => [
                RouteLogJsonRequest::PARAM__PATH => [
                    ISampleParameter::FIELD__NAME => RouteLogJsonRequest::PARAM__PATH,
                    ISampleParameter::FIELD__VALUE => '/logs/test.json'
                ]
            ]
        ]));

        putenv('EXTAS__JIRA_MOCK__HOST=test');

        $process = new Process('php -S localhost:8080 -t ' . getcwd() . '/src/web');
        $process->start();

        $client = new Client(['http_errors' => false]);

        $client->request("POST", "http://localhost:8080/test", [
            'json' => [
                'test' => 'is ok'
            ]
        ]);
        $this->assertTrue(file_exists(getcwd() . '/logs/test.json'));
        $this->assertEquals('{"test":"is ok"}', file_get_contents(getcwd() . '/logs/test.json'));

        $process->stop();
    }
}