<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/StatementBuilder/SelectStatementBuilder.php';
require_once SRC_ROOT . '/Exception/QueryBuilderException.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';
require_once __DIR__ . '/../../Fixtures/QueryBuilderMock.php';
require_once __DIR__ . '/../../Fixtures/StatementBuilderMock.php';

use Bloatless\QueryBuilder\StatementBuilder\SelectStatementBuilder;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use Bloatless\QueryBuilder\Test\Fixtures\QueryBuilderMock;
use Bloatless\QueryBuilder\Test\Fixtures\StatementBuilderMock;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class QueryBuilderTest extends AbstractQueryBuilderTest
{
    public function testCanBeInitialized()
    {

        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);
        $this->assertInstanceOf(QueryBuilderMock::class, $queryBuilder);
    }

    public function testSetGetStatementBuilder()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);

        $selectStatementBuilder = new SelectStatementBuilder;
        $queryBuilder->setStatementBuilder($selectStatementBuilder);
        $this->assertInstanceOf(SelectStatementBuilder::class, $queryBuilder->getStatementBuilder());
    }

    public function testExecuteWithValidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);
        $statement = $this->getConnection()->prepare('SELECT COUNT(*) FROM `customers`');
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->execute($statement));
    }

    public function testProvideStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);
        $queryBuilder->setTestStatement('SELECT COUNT(*) FROM `customers`', []);
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->exposedProvideStatement());
    }

    public function testPrepareStatementWithValidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);
        $statement = $queryBuilder->exposedPrepareStatement(
            'SELECT * FROM customers WHERE customer_id IN (:p1,:p2,:p3,:p4)',
            [
                'p1' => 1,
                'p2' => false,
                'p3' => null,
                'p4' => '2'
            ]
        );
        $this->assertInstanceOf(\PDOStatement::class, $statement);
    }

    public function testPrepareStatementWithInvalidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->getConnection(), $statementBuilder);
        $this->expectException(QueryBuilderException::class);
        $queryBuilder->exposedPrepareStatement('SELECT * FROM foo', []);
    }
}
