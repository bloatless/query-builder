<?php

namespace Bloatless\QueryBuilder\Test\Unit\StatementBuilder;

require_once SRC_ROOT . '/StatementBuilder/RawStatementBuilder.php';

use Bloatless\QueryBuilder\StatementBuilder\RawStatementBuilder;
use PHPUnit\Framework\TestCase;

class RawStatementBuilderTest extends TestCase
{
    public function testPrepareRawStatementWithoutBindings()
    {
        $statementBuilder = new RawStatementBuilder;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo", []);
        $this->assertEquals('SELECT * FROM foo', $statementBuilder->getStatement());
    }

    public function testPrepareRawStatementWithBindings()
    {
        $statementBuilder = new RawStatementBuilder;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo WHERE id = :id", [
            'id' => 42
        ]);
        $this->assertEquals("SELECT * FROM foo WHERE id = :id", $statementBuilder->getStatement());
        $this->assertEquals(['id' => 42], $statementBuilder->getBindingValues());
    }
}
