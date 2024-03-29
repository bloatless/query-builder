<?php

declare(strict_types=1);

namespace Bloatless\QueryBuilder\StatementBuilder;

require_once __DIR__ . '/../Exception/QueryBuilderException.php';
require_once __DIR__ . '/StatementBuilder.php';

use Bloatless\QueryBuilder\Exception\QueryBuilderException;

class InsertStatementBuilder extends StatementBuilder
{
    public function __construct()
    {
        $this->statement = 'INSERT';
    }

    /**
     * Adds possible flags to statement.
     *
     * @param array $flags
     * @return void
     */
    public function addFlags(array $flags): void
    {
        if (isset($flags['ignore']) && $flags['ignore'] === true) {
            $this->statement .= ' IGNORE';
        }
    }

    /**
     * Adds table name to insert statement.
     *
     * @param string $table
     * @return void
     */
    public function addInto(string $table): void
    {
        $table = $this->quoteName($table);
        $this->statement .= ' INTO ' . $table;
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds column names and values to insert statement.
     *
     * @param array $rows
     * @throws QueryBuilderException
     * @return void
     */
    public function addRows(array $rows): void
    {
        if (empty($rows)) {
            throw new QueryBuilderException('Can not perform insert with empty rows.');
        }

        // Collect column names and add to statement:
        $cols = array_keys(reset($rows));
        foreach ($cols as $i => $col) {
            $cols[$i] = $this->quoteName($col);
        }
        $pattern = " (%s) VALUES" . PHP_EOL;
        $this->statement .= sprintf($pattern, implode(',', $cols));

        // Add values for each row to insert statement:
        $rowValues = [];
        foreach ($rows as $row) {
            $placeholders = $this->bindRowValues($row);
            array_push($rowValues, sprintf('(%s)', implode(',', $placeholders)));
        }
        $this->statement .= implode(',', $rowValues);
    }

    /**
     * Binds values of single to statement and returns placeholders.
     *
     * @param array $row
     * @return array
     */
    private function bindRowValues(array $row): array
    {
        $placeholders = [];
        foreach ($row as $key => $value) {
            $placeholder = $this->addBindingValue($key, $value);
            array_push($placeholders, $placeholder);
        }
        return $placeholders;
    }
}
