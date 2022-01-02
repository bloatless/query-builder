<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder;

require_once __DIR__ . '/ConnectionAdapter/PdoMysql.php';
require_once __DIR__ . '/ConnectionAdapter/PdoSqlite.php';
require_once __DIR__ . '/Exception/QueryBuilderException.php';
require_once __DIR__ . '/QueryBuilder/DeleteQueryBuilder.php';
require_once __DIR__ . '/QueryBuilder/InsertQueryBuilder.php';
require_once __DIR__ . '/QueryBuilder/RawQueryBuilder.php';
require_once __DIR__ . '/QueryBuilder/SelectQueryBuilder.php';
require_once __DIR__ . '/QueryBuilder/UpdateQueryBuilder.php';
require_once __DIR__ . '/StatementBuilder/DeleteStatementBuilder.php';
require_once __DIR__ . '/StatementBuilder/InsertStatementBuilder.php';
require_once __DIR__ . '/StatementBuilder/RawStatementBuilder.php';
require_once __DIR__ . '/StatementBuilder/SelectStatementBuilder.php';
require_once __DIR__ . '/StatementBuilder/UpdateStatementBuilder.php';

use Bloatless\QueryBuilder\ConnectionAdapter\PdoMysql;
use Bloatless\QueryBuilder\ConnectionAdapter\PdoSqlite;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use Bloatless\QueryBuilder\QueryBuilder\DeleteQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\InsertQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\RawQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\SelectQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\UpdateQueryBuilder;
use Bloatless\QueryBuilder\StatementBuilder\DeleteStatementBuilder;
use Bloatless\QueryBuilder\StatementBuilder\InsertStatementBuilder;
use Bloatless\QueryBuilder\StatementBuilder\RawStatementBuilder;
use Bloatless\QueryBuilder\StatementBuilder\SelectStatementBuilder;
use Bloatless\QueryBuilder\StatementBuilder\UpdateStatementBuilder;

class QueryBuilder
{
    /**
     * @var array $connections
     */
    protected $connections = [];

    /**
     * @var array $credentials
     */
    protected $credentials = [];

    /**
     * @var string $defaultConnectionName
     */
    protected $defaultConnectionName = '';

    /**
     * @param array $credentials
     * @param string $defaultConnectionName
     */
    public function __construct(array $credentials, string $defaultConnectionName)
    {
        $this->credentials = $credentials;
        $this->defaultConnectionName = $defaultConnectionName;
    }

    /**
     * Creates a new InsertQueryBuilder instance.
     *
     * @param string $connectionName
     * @return InsertQueryBuilder
     * @throws QueryBuilderException
     */
    public function makeInsert(string $connectionName = ''): InsertQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new InsertStatementBuilder;
        return new InsertQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new SelectQueryBuilder instance.
     *
     * @param string $connectionName
     * @return SelectQueryBuilder
     * @throws QueryBuilderException
     */
    public function makeSelect(string $connectionName = ''): SelectQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new SelectStatementBuilder;
        return new SelectQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new UpdateQueryBuilder instance.
     *
     * @param string $connectionName
     * @throws QueryBuilderException
     * @return UpdateQueryBuilder
     */
    public function makeUpdate(string $connectionName = ''): UpdateQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new UpdateStatementBuilder;
        return new UpdateQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new UpdateQueryBuilder instance.
     *
     * @param string $connectionName
     * @throws QueryBuilderException
     * @return DeleteQueryBuilder
     */
    public function makeDelete(string $connectionName = ''): DeleteQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new DeleteStatementBuilder;
        return new DeleteQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new raw RawQueryBuilderInstance.
     *
     * @param string $connectionName
     * @return RawQueryBuilder
     * @throws QueryBuilderException
     */
    public function makeRaw(string $connectionName = ''): RawQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new RawStatementBuilder;
        return new RawQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Provides a database connection (PDO object).
     *
     * @param string $connectionName
     * @return \PDO
     * @throws QueryBuilderException
     */
    public function provideConnection(string $connectionName = ''): \PDO
    {
        if (empty($connectionName)) {
            $connectionName = $this->defaultConnectionName;
        }
        if ($this->hasConnection($connectionName) === true) {
            return $this->getConnection($connectionName);
        }

        $dbConfig = $this->credentials[$connectionName];

        switch ($dbConfig['driver']) {
            case 'mysql':
                $adapter = new PdoMysql;
                break;
            case 'sqlite':
                $adapter = new PdoSqlite;
                break;
            default:
                throw new QueryBuilderException('Unsupported database driver. Check config.');
        }

        $connection = $adapter->connect($dbConfig);
        $this->addConnection($connectionName, $connection);

        return $connection;
    }

    /**
     * Checks if database connection with given name exists.
     *
     * @param string $connectionName
     * @return bool
     */
    public function hasConnection(string $connectionName): bool
    {
        return isset($this->connections[$connectionName]);
    }

    /**
     * Adds database connection to pool.
     *
     * @param string $connectionName
     * @param \PDO $connection
     * @return void
     */
    public function addConnection(string $connectionName, \PDO $connection): void
    {
        $this->connections[$connectionName] = $connection;
    }

    /**
     * Retrieves a database connection.
     *
     * @param string $connectionName
     * @return \PDO
     * @throws QueryBuilderException
     */
    public function getConnection(string $connectionName): \PDO
    {
        if (!isset($this->connections[$connectionName])) {
            throw new QueryBuilderException(sprintf('Connection (%s) not found in pool.', $connectionName));
        }
        return $this->connections[$connectionName];
    }
}
