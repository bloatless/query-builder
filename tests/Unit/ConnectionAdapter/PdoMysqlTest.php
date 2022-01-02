<?php

namespace Bloatless\QueryBuilder\Test\Unit\ConnectionAdapter;

require_once SRC_ROOT . '/ConnectionAdapter/PdoMysql.php';
require_once SRC_ROOT . '/Exception/QueryBuilderException.php';

use Bloatless\QueryBuilder\ConnectionAdapter\PdoMysql;
use Bloatless\QueryBuilder\Exception\QueryBuilderException;
use PHPUnit\Framework\TestCase;

class PdoMysqlTest extends TestCase
{
    public $config;

    public $credentials;

    public function setUp(): void
    {
        $configData = include TESTS_ROOT . '/Fixtures/config/config.php';
        $this->config = $configData['db'];
        $this->credentials = $this->config['connections']['db1'];
    }

    public function testConnectWithValidCredentails()
    {
        $credentials = $this->credentials;
        $credentials['port'] = 3306;
        $adapter = new PdoMysql;
        $connection = $adapter->connect($credentials);
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    public function testConnectWithInvalidCredentails()
    {
        $this->expectException(QueryBuilderException::class);
        $adapter = new PdoMysql;
        $adapter->connect([]);
    }

    public function testConnectWithInvalidTimezone()
    {
        $adapter = new PdoMysql;
        $credentials = $this->credentials;
        $credentials['timezone'] = 'Springfield';
        $this->expectException(\Exception::class);
        $connection = $adapter->connect($credentials);
    }
}
