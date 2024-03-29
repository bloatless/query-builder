<?php

namespace Bloatless\QueryBuilder\Test\Unit\StatementBuilder;

require_once SRC_ROOT . '/Exception/QueryBuilderException.php';
require_once SRC_ROOT . '/StatementBuilder/InsertStatementBuilder.php';

use Bloatless\QueryBuilder\StatementBuilder\InsertStatementBuilder;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use PHPUnit\Framework\TestCase;

class InsertStatementBuilderTest extends TestCase
{
    public function testInitialization()
    {
        $builder = new InsertStatementBuilder;
        $this->assertEquals('INSERT', $builder->getStatement());
    }

    public function testAddFlags()
    {
        $builder = new InsertStatementBuilder;
        $builder->addFlags(['ignore' => true]);
        $this->assertEquals('INSERT IGNORE', $builder->getStatement());
    }

    public function testAddInto()
    {
        $builder = new InsertStatementBuilder;
        $builder->addInto('customers');
        $this->assertEquals("INSERT INTO `customers`".PHP_EOL, $builder->getStatement());
    }

    public function testAddRows()
    {
        $builder = new InsertStatementBuilder;
        $builder->addRows([['firstname' => 'Homer']]);
        $this->assertEquals('INSERT (`firstname`) VALUES'.PHP_EOL.'(:firstname)', $builder->getStatement());
    }

    public function testAddRowsWithEmptyRows()
    {
        $builder = new InsertStatementBuilder;
        $this->expectException(QueryBuilderException::class);
        $builder->addRows([]);
    }
}
