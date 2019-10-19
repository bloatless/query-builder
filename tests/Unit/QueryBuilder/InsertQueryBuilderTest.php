<?php

namespace Bloatless\QueryBuilder\Tests\Unit\QueryBuilder;

use Bloatless\QueryBuilder\Factory;
use Bloatless\QueryBuilder\QueryBuilder\InsertQueryBuilder;
use Bloatless\QueryBuilder\Tests\Unit\DatabaseTest;

class InsertQueryBuilderTest extends DatabaseTest
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

    public function testIgnore()
    {
        $builder = $this->factory->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->ignore());
    }

    public function testInto()
    {
        $builder = $this->factory->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->into('customers'));
    }
    public function testRow()
    {
        $builder = $this->factory->makeInsert();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $this->getRowCount('customers'));
    }

    public function testRows()
    {
        $builder = $this->factory->makeInsert();
        $builder->into('customers')
            ->rows([
                [
                    'firstname' => 'Santa',
                    'lastname' => 'Simpson',
                    'email' => 'santa@simpsons.com'
                ],
                [
                    'firstname' => 'Snowball',
                    'lastname' => 'Simpson',
                    'email' => 'snowball@simpsons.com'
                ]
            ]);
        $this->assertEquals(6, $this->getRowCount('customers'));
    }

    public function testGetLastInsertId()
    {
        $builder = $this->factory->makeInsert();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $builder->getLastInsertId());
    }

    public function testReset()
    {
        $builder = $this->factory->makeInsert()
            ->into('foobar');
        $builder->reset();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $builder->getLastInsertId());
    }
}