<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/QueryBuilder.php';
require_once SRC_ROOT . '/QueryBuilderFactory.php';
require_once SRC_ROOT . '/QueryBuilder/SelectQueryBuilder.php';
require_once SRC_ROOT . '/Exception/QueryBuilderException.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';

use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\QueryBuilder\SelectQueryBuilder;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class SelectQueryBuilderTest extends AbstractQueryBuilderTest
{
    private QueryBuilder $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $this->db = $factory->make();
    }

    public function testSetter()
    {
        $builder = $this->db->makeSelect()
            ->distinct()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->join('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->leftJoin('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->rightJoin('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->groupBy('orders.order_id')
            ->orderBy('customers.firstname', 'asc')
            ->having('cnt', '>', 3)
            ->orHaving('cnt', '<', 10)
            ->limit(3)
            ->offset(1);
        $this->assertInstanceOf(SelectQueryBuilder::class, $builder);
    }

    public function testGet()
    {
        $result = $this->db->makeSelect()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->get();
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
    }

    public function testGetWithEmptyResult()
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->get();
        $this->assertEquals([], $result);
    }

    public function testFirst()
    {
        // test with result:
        $result = $this->db->makeSelect()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->first();
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals($result->firstname, 'Homer');
    }

    public function testFirstWithEmptyResult()
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->first();
        $this->assertEquals(null, $result);
    }

    public function testPluckWithColumnOnly()
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 1)
            ->pluck('firstname');
        $this->assertEquals([0 => 'Homer'], $result);
    }

    public function testPluckWithColumnAndKeyBy()
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 2)
            ->pluck('firstname', 'customer_id');
        $this->assertEquals([2 => 'Marge'], $result);
    }

    public function testPluckWithEmptyResult()
    {
        $result = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->pluck('firstname', 'customer_id');
        $this->assertEquals([], $result);
    }

    public function testPluckWithInvalidColumn()
    {
        $this->expectException(QueryBuilderException::class);
        $this->db->makeSelect()
            ->from('customers')
            ->pluck('foo');
    }

    public function testPluckWithInvalidKeyBy()
    {
        $this->expectException(QueryBuilderException::class);
        $this->db->makeSelect()
            ->from('customers')
            ->pluck('firstname', 'foo');
    }

    public function testCount()
    {
        $count = $this->db->makeSelect()
            ->from('customers')
            ->count();
        $this->assertEquals(4, $count);
    }

    public function testReset()
    {
        $builder = $this->db->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $result = $builder->from('customers')
            ->whereEquals('customer_id', 2)
            ->first();
        $this->assertIsObject($result);
        $this->assertEquals('Marge', $result->firstname);
    }
}
