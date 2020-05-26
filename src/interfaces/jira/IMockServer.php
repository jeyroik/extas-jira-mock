<?php
namespace extas\interfaces\jira;

use extas\interfaces\IItem;

/**
 * Interface IMockServer
 *
 * @package extas\interfaces\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IMockServer extends IItem
{
    public const SUBJECT = 'extas.jira.server.mock';

    public const FIELD__HOST = 'host';
    public const FIELD__BASE_PATH = 'base_path';

    /**
     * @return string
     */
    public function getBasePath(): string;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * Run server
     */
    public function run(): void;
}
