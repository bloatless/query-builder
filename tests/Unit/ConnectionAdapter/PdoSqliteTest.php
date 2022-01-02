<?php

namespace Bloatless\QueryBuilder\Test\Unit\ConnectionAdapter;

require_once SRC_ROOT . '/ConnectionAdapter/PdoSqlite.php';

use Bloatless\QueryBuilder\ConnectionAdapter\PdoSqlite;
use PHPUnit\Framework\TestCase;

class PdoSqliteTest extends TestCase
{
    public $config;

    public $defaultCredentials;

    public function setUp(): void
    {
        $configData = include TESTS_ROOT . '/Fixtures/config/config.php';
        $this->config = $configData['db'];
        $this->defaultCredentials = $this->config['connections']['db2'];
    }

    public function testConnectWithValidCredentails()
    {
        $credentials = $this->defaultCredentials;
        $adapter = new PdoSqlite;
        $connection = $adapter->connect($credentials);
        $this->assertInstanceOf(\PDO::class, $connection);
    }
}
