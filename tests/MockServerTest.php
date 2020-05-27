<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\extensions\TSnuffExtensions;
use extas\components\http\TSnuffHttp;
use extas\components\jira\JiraRoute;
use extas\components\jira\JiraRouteRepository;
use extas\components\jira\MockServer;
use extas\components\jira\routes\RouteLogJsonRequest;
use extas\components\jira\routes\RoutePrepared;
use extas\interfaces\jira\IMockServer;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\samples\parameters\ISampleParameter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MockServerTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class MockServerTest extends TestCase
{
    use TSnuffExtensions;
    use TSnuffHttp;

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
        $this->createRoute('/prepared.json', RoutePrepared::class);
        $response = $this->runServer();

        $this->assertEquals(
            '{"test": "is ok"}',
            (string) $response->getBody(),
            'Response mismatched: ' . $response->getBody()
        );
    }

    public function testRoutePreparedMissed()
    {
        $this->createRoute('/prepared.unknown.json', RoutePrepared::class);
        $response = $this->runServer();

        $this->assertEquals(
            'Missed prepared path with suffix "/prepared.unknown.json"',
            (string) $response->getBody(),
            'Response mismatched: ' . $response->getBody()
        );
    }

    public function testRouteLogJsonRequest()
    {
        $this->createRoute('/log.test.json', RouteLogJsonRequest::class);
        $this->runServer();
        $this->assertTrue(file_exists(getcwd() . '/tests/log.test.json'), 'Missed log file');
        $this->assertTrue(
            (bool) strpos(file_get_contents(getcwd() . '/tests/log.test.json'), '{"test": "is ok"}'),
            'Log contents mismatched'
        );
        unlink(getcwd() . '/tests/log.test.json');
    }

    public function testUnknownPath()
    {
        $response = $this->runServer('/unknown');
        $this->assertEquals(
            json_encode(['error' => 'Unknown path "/unknown"']),
            (string) $response->getBody(),
            'Response mismatched: ' . $response->getBody()
        );
    }

    /**
     * @param string $path
     * @return ResponseInterface
     */
    protected function runServer(string $path = '/test'): ResponseInterface
    {
        $server = $this->getServer();

        return $server->run(
            $this->getPsrRequest('', [], '', 'GET', $path),
            $this->getPsrResponse()
        );
    }

    /**
     * @param string $path
     * @param string $class
     */
    protected function createRoute(string $path, string $class): void
    {
        $this->routeRepo->create(new JiraRoute([
            JiraRoute::FIELD__NAME => '/test',
            JiraRoute::FIELD__CLASS => $class,
            JiraRoute::FIELD__PARAMETERS => [
                'path' => [
                    ISampleParameter::FIELD__NAME => 'path',
                    ISampleParameter::FIELD__VALUE => $path
                ]
            ]
        ]));
    }

    /**
     * @return IMockServer
     */
    protected function getServer(): IMockServer
    {
        return new MockServer([
            MockServer::FIELD__HOST => 'test',
            MockServer::FIELD__BASE_PATH => getcwd() . '/tests'
        ]);
    }
}
