<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once SRC_ROOT . '/QueryBuilder.php';
require_once SRC_ROOT . '/QueryBuilderFactory.php';
require_once SRC_ROOT . '/QueryBuilder/InsertQueryBuilder.php';

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';

use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\QueryBuilder\InsertQueryBuilder;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class InsertQueryBuilderTest extends AbstractQueryBuilderTest
{
    private QueryBuilder $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $this->db = $factory->make();
    }

    public function testIgnore()
    {
        $builder = $this->db->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->ignore());
    }

    public function testInto()
    {
        $builder = $this->db->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->into('customers'));
    }
    public function testRow()
    {
        $builder = $this->db->makeInsert();
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
        $builder = $this->db->makeInsert();
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
        $builder = $this->db->makeInsert();
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
        $builder = $this->db->makeInsert()
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
