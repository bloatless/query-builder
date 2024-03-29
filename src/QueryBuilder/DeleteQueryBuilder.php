<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder\QueryBuilder;

require_once __DIR__ . '/WhereQueryBuilder.php';

/**
 * @property \Bloatless\QueryBuilder\StatementBuilder\DeleteStatementBuilder $statementBuilder
 */
class DeleteQueryBuilder extends WhereQueryBuilder
{
    /**
     * @var string $from
     */
    protected $from = '';

    /**
     * Sets table to delete from.
     *
     * @param string $from
     * @return DeleteQueryBuilder
     */
    public function from(string $from): DeleteQueryBuilder
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Executes delete statement and returns affected rows.
     *
     * @throws \Bloatless\QueryBuilder\Exception\QueryBuilderException
     * @return int
     */
    public function delete(): int
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->rowCount();
    }

    /**
     * @inheritdoc
     */
    public function reset(): void
    {
        $this->from = '';
        $this->where = [];
    }

    /**
     * Builds the DELETE statement with all previously set attributes.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addFrom($this->from);
        $this->statementBuilder->addWhere($this->where);
        return $this->statementBuilder->getStatement();
    }
}
