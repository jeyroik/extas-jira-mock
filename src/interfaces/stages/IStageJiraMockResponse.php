<?php
namespace extas\interfaces\stages;

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
    public function __invoke(string &$response): void;
}
