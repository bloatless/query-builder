<?php

namespace Bloatless\QueryBuilder\Test\Unit;

use Bloatless\QueryBuilder\ConnectionAdapter\PdoMysql;
use Bloatless\QueryBuilder\QueryBuilder;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use Bloatless\QueryBuilder\QueryBuilder\DeleteQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\InsertQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\RawQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\SelectQueryBuilder;
use Bloatless\QueryBuilder\QueryBuilder\UpdateQueryBuilder;

class QueryBuilderTest extends AbstractQueryBuilderTest
{
    public function testMakeInsert()
    {
        $db = $this->provideQueryBuilder();
        $this->assertInstanceOf(InsertQueryBuilder::class, $db->makeInsert());
    }

    public function testMakeSelect()
    {
        $db = $this->provideQueryBuilder();
        $this->assertInstanceOf(SelectQueryBuilder::class, $db->makeSelect());
    }

    public function testMakeUpdate()
    {
        $db = $this->provideQueryBuilder();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $db->makeUpdate());
    }

    public function testMakeDelete()
    {
        $db = $this->provideQueryBuilder();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $db->makeDelete());
    }

    public function testMakeRaw()
    {
        $db = $this->provideQueryBuilder();
        $this->assertInstanceOf(RawQueryBuilder::class, $db->makeRaw());
    }

    public function testProvideConnection()
    {
        $db = $this->provideQueryBuilder();

        // default connection:
        $connection = $db->provideConnection();
        $this->assertInstanceOf(\PDO::class, $connection);

        // named connection:
        $connection = $db->provideConnection('db1');
        $this->assertInstanceOf(\PDO::class, $connection);

        unset($db, $connection);

        // invalid driver:
        $configData = include TESTS_ROOT . '/Fixtures/config/config.php';
        $configData['db']['connections']['db1']['driver'] = 'foo';
        $db = new QueryBuilder($configData['db']['connections'], 'db1');
        $this->expectException(QueryBuilderException::class);
        $db->provideConnection();
        unset($config, $db);

        // invalid credentials:
        $configData['db']['connections']['db1']['driver'] = 'mysql';
        $configData['db']['connections']['db1']['username'] = 'foo';
        $db = new QueryBuilder($configData['db'], 'db1');
        $this->expectException(QueryBuilderException::class);
        $db->provideConnection();
    }

    public function testAddGetConnection()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';

        $db = $this->provideQueryBuilder();
        $this->assertFalse($db->hasConnection('db1'));

        $adapter = new PdoMysql;
        $connection = $adapter->connect($config['db']['connections']['db1']);
        $db->addConnection('foo', $connection);
        $this->assertTrue($db->hasConnection('foo'));
        $this->assertInstanceOf(\PDO::class, $db->getConnection('foo'));

        $this->expectException(QueryBuilderException::class);
        $db->getConnection('bar');
    }

    private function provideQueryBuilder()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $db = new QueryBuilder($config['db']['connections'], 'db1');

        return $db;
    }
}
