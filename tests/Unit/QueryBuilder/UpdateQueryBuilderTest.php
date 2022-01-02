<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/QueryBuilder.php';
require_once SRC_ROOT . '/QueryBuilderFactory.php';
require_once SRC_ROOT . '/QueryBuilder/UpdateQueryBuilder.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';

use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\QueryBuilder\UpdateQueryBuilder;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class UpdateQueryBuilderTest extends AbstractQueryBuilderTest
{
    private QueryBuilder $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $this->db = $factory->make();
    }

    public function testTable()
    {
        $builder = $this->db->makeUpdate();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $builder->table('customers'));
    }

    public function testUpdate()
    {
        $builder = $this->db->makeUpdate();
        $rowsAffected = $builder->table('customers')
            ->whereEquals('firstname', 'Homer')
            ->update([
               'firstname' => 'Max',
            ]);
        $this->assertEquals(1, $rowsAffected);
    }

    public function testReset()
    {
        $builder = $this->db->makeUpdate()
            ->table('foobar')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $affectedRows = $builder->table('customers')
            ->whereEquals('customer_id', 1)
            ->update(['firstname' => 'Max']);
        $this->assertEquals(1, $affectedRows);
    }
}
