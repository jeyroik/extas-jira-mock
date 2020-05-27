<?php
namespace extas\interfaces\stages;

use Psr\Http\Message\RequestInterface;

/**
 * Interface IStageJiraMockResponse
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageJiraMockResponse
{
    public const NAME = 'extas.jira.mock.response';

    /**
     * @param string $response
     */
    public function __invoke(RequestInterface $request, string &$response): void;
}
