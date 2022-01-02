<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder;

require_once __DIR__ . '/QueryBuilder.php';
require_once __DIR__ . '/Exception/QueryBuilderException.php';

use Bloatless\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactory
{
    /**
     * @var array $config
     */
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function make(): QueryBuilder
    {
        if (empty($this->config['db'])) {
            throw new QueryBuilderException('Can not provide component "Database". Configuration missing.');
        }

        $dbConfig = $this->config['db'];
        $credentials = $dbConfig['connections'];
        $defaultConnectionName = $dbConfig['default_connection'] ?? key(reset($dbConfig['connections']));

        return new QueryBuilder($credentials, $defaultConnectionName);
    }
}
