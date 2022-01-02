<?php

namespace Bloatless\QueryBuilder\Test\Unit;

require_once __DIR__ . '/AbstractQueryBuilderTest.php';
require_once __DIR__ . '/../../src/QueryBuilderFactory.php';


use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\QueryBuilderFactory;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactoryTest extends AbstractQueryBuilderTest
{

    public function testMake()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new QueryBuilderFactory($config);
        $db = $factory->make();
        $this->assertInstanceOf(QueryBuilder::class, $db);
    }

    public function testInitWithoutConnections()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        unset($config['db']);
        $factory = new QueryBuilderFactory($config);
        $this->expectException(QueryBuilderException::class);
        $factory->make();
    }
}
