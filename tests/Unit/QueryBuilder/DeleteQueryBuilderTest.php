<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/QueryBuilder.php';
require_once SRC_ROOT . '/QueryBuilderFactory.php';
require_once SRC_ROOT . '/QueryBuilder/DeleteQueryBuilder.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';

use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\QueryBuilder\DeleteQueryBuilder;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class DeleteQueryBuilderTest extends AbstractQueryBuilderTest
{
    private QueryBuilder $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $this->db = $factory->make();
    }

    public function testFrom()
    {
        $queryBuilder = $this->db->makeDelete();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $queryBuilder->from('customers'));
    }

    public function testDelete()
    {
        $queryBuilder = $this->db->makeDelete();
        $affectedRows = $queryBuilder->from('customers')
            ->whereEquals('customer_id', 4)
            ->delete();
        $this->assertEquals(1, $affectedRows);
        $this->assertEquals(3, $this->getRowCount('customers'));
    }

    public function testReset()
    {
        $builder = $this->db->makeDelete()
            ->from('customers')
            ->whereEquals('customer_id', 1);
        $builder->reset();
        $affectedRows = $builder->from('customers')
            ->whereEquals('customer_id', 42)
            ->delete();
        $this->assertEquals(0, $affectedRows);
        $this->assertEquals(4, $this->getRowCount('customers'));
    }
}
