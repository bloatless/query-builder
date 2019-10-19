<?php

namespace Bloatless\QueryBuilder\Tests\Unit\QueryBuilder;

use Bloatless\QueryBuilder\Factory;
use Bloatless\QueryBuilder\QueryBuilder\UpdateQueryBuilder;
use Bloatless\QueryBuilder\Tests\Unit\DatabaseTest;

class UpdateQueryBuilderTest extends DatabaseTest
{
    /**
     * @var array $config
     */
    public $config;

    /**
     * @var Factory $factory
     */
    public $factory;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->config = $config['db'];
        $this->factory = new Factory($this->config);
    }

    public function testTable()
    {
        $builder = $this->factory->makeUpdate();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $builder->table('customers'));
    }

    public function testUpdate()
    {
        $builder = $this->factory->makeUpdate();
        $rowsAffected = $builder->table('customers')
            ->whereEquals('firstname', 'Homer')
            ->update([
               'firstname' => 'Max',
            ]);
        $this->assertEquals(1, $rowsAffected);
    }

    public function testReset()
    {
        $builder = $this->factory->makeUpdate()
            ->table('foobar')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $affectedRows = $builder->table('customers')
            ->whereEquals('customer_id', 1)
            ->update(['firstname' => 'Max']);
        $this->assertEquals(1, $affectedRows);
    }
}