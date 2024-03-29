<?php

namespace Bloatless\QueryBuilder\Test\Unit\StatementBuilder;

require_once SRC_ROOT . '/StatementBuilder/DeleteStatementBuilder.php';

use Bloatless\QueryBuilder\StatementBuilder\DeleteStatementBuilder;
use PHPUnit\Framework\TestCase;

class DeleteStatementBuilderTest extends TestCase
{
    public function testInitialization()
    {
        $builder = new DeleteStatementBuilder;
        $this->assertEquals('DELETE', $builder->getStatement());
    }

    public function testAddFrom()
    {
        $builder = new DeleteStatementBuilder;
        $builder->addFrom('customers');
        $this->assertEquals("DELETE FROM `customers`" . PHP_EOL, $builder->getStatement());
    }
}
