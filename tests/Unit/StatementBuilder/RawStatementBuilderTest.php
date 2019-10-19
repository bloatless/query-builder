<?php

namespace Bloatless\QueryBuilder\Tests\Unit\StatementBuilder;

use Bloatless\QueryBuilder\StatementBuilder\RawStatementBuider;
use PHPUnit\Framework\TestCase;

class RawStatementBuilderTest extends TestCase
{
    public function testPrepareRawStatementWithoutBindings()
    {
        $statementBuilder = new RawStatementBuider;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo", []);
        $this->assertEquals('SELECT * FROM foo', $statementBuilder->getStatement());
    }

    public function testPrepareRawStatementWithBindings()
    {
        $statementBuilder = new RawStatementBuider;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo WHERE id = :id", [
            'id' => 42
        ]);
        $this->assertEquals("SELECT * FROM foo WHERE id = :id", $statementBuilder->getStatement());
        $this->assertEquals(['id' => 42], $statementBuilder->getBindingValues());
    }
}