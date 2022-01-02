<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder\StatementBuilder;

require_once __DIR__ . '/WhereStatementBuilder.php';

class DeleteStatementBuilder extends WhereStatementBuilder
{
    public function __construct()
    {
        $this->statement = 'DELETE';
    }

    /**
     * Adds table to delete from to statement.
     *
     * @param string $from
     */
    public function addFrom(string $from): void
    {
        $this->statement .= ' FROM ' . $this->quoteName($from) . PHP_EOL;
    }
}
