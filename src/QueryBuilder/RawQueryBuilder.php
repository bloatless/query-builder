<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder\QueryBuilder;

require_once __DIR__ . '/QueryBuilder.php';
require_once __DIR__ . '/RawQueryBuilder.php';

/**
 * @property \Bloatless\QueryBuilder\StatementBuilder\RawStatementBuilder $statementBuilder
 */
class RawQueryBuilder extends QueryBuilder
{
    /**
     * @var string $statement
     */
    protected $statement;

    /**
     * @var array $bindings
     */
    protected $bindings = [];

    /**
     * Prepares a raw statement.
     *
     * @param string $statement
     * @param array $bindings
     * @return RawQueryBuilder
     */
    public function prepare(string $statement, array $bindings = []): RawQueryBuilder
    {
        $this->statement = $statement;
        $this->bindings = $bindings;

        return $this;
    }

    /**
    * Executes raw statement and returns all matching rows as array of objects.
    *
    * @return array
     * @throws \Bloatless\QueryBuilder\Exception\QueryBuilderException
    */
    public function get(): array
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Executes a raw statement.
     *
     * @throws \Bloatless\QueryBuilder\Exception\QueryBuilderException
     */
    public function run(): void
    {
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
    }

    /**
     * @inheritdoc
     */
    public function reset(): void
    {
        $this->statement = '';
        $this->bindings = [];
    }

    /**
     * Builds/Prepares the raw statement for execution.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->prepareRawStatement($this->statement, $this->bindings);
        return $this->statementBuilder->getStatement();
    }
}
