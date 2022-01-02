<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder\ConnectionAdapter;

require_once __DIR__ . '/ConnectionAdapterInterface.php';
require_once __DIR__ . '/../Exception/QueryBuilderException.php';

use Bloatless\QueryBuilder\Exception\QueryBuilderException;

class PdoSqlite implements ConnectionAdapterInterface
{
    /**
     * @param array $credentials
     * @throws QueryBuilderException
     * @throws \Exception
     * @return \PDO
     */
    public function connect(array $credentials): \PDO
    {
        $database = $credentials['database'] ?? '';
        $dsn = 'sqlite:' . $database;

        try {
            $pdo = new \PDO($dsn);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        } catch (\PDOException $e) {
            throw new QueryBuilderException(sprintf('Error connecting to database (%s)', $e->getMessage()));
        }
    }
}
