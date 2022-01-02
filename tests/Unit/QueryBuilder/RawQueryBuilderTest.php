<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/QueryBuilder.php';
require_once SRC_ROOT . '/QueryBuilderFactory.php';
require_once SRC_ROOT . '/QueryBuilder/RawQueryBuilder.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';

use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\QueryBuilder\RawQueryBuilder;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class RawQueryBuilderTest extends AbstractQueryBuilderTest
{
    private QueryBuilder $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $this->db = $factory->make();
    }

    public function testPrepare()
    {
        $builder = $this->db->makeRaw();
        $this->assertInstanceOf(RawQueryBuilder::class, $builder->prepare('SELECT * FROM `customers`'));
    }

    public function testGet()
    {
        $builder = $this->db->makeRaw();
        $builder->prepare('SELECT `firstname` FROM `customers` WHERE `customer_id` = :id', ['id' => 1]);
        $result = $builder->get();
        $this->assertIsArray($result);
        $this->assertEquals('Homer', $result[0]->firstname);
    }

    public function testRun()
    {
        $builder = $this->db->makeRaw();
        $builder->prepare('INSERT INTO `customers` (`firstname`, `lastname`) VALUES (:fn, :ln)', [
           'fn' => 'Maggie',
           'ln' => 'Simpson',
        ])->run();
        $this->assertEquals(5, $this->getRowCount('customers'));
    }

    public function testReset()
    {
        $builder = $this->db->makeRaw();
        $builder->prepare('SELECT FROM `foo`', ['foo' => 'bar']);
        $builder->reset();
        $res = $builder->prepare('SELECT COUNT(*) AS cnt FROM `customers`')->get();
        $this->assertIsArray($res);
        $this->assertEquals(4, $res[0]->cnt);
    }
}
