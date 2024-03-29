<?php

namespace Bloatless\QueryBuilder\Test\Unit;

require_once SRC_ROOT . '/QueryBuilder.php';

use Bloatless\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

abstract class AbstractQueryBuilderTest extends TestCase
{
    /**
     * @var \PDO $pdo
     */
    private static $pdo = null;

    /**
     * Initializes database connection.
     *
     * @return \PDO
     */
    final public function getConnection()
    {
        if (self::$pdo === null) {
            $config = include TESTS_ROOT . '/Fixtures/config/config.php';
            $defaultConnectionName = $config['db']['default_connection'];
            $queryBuilder = new QueryBuilder($config['db']['connections'], $defaultConnectionName);
            self::$pdo = $queryBuilder->provideConnection();
        }

        return self::$pdo;
    }

    public function setUp(): void
    {
        $this->initDatabase();
        $this->seedDatabase();
    }

    public function tearDown(): void
    {
        $this->tearDownDatabase();
    }

    public function resetDatabase()
    {
        $this->tearDownDatabase();
        $this->initDatabase();
        $this->seedDatabase();
    }

    public function seedDatabase()
    {
        $statement = file_get_contents(TESTS_ROOT . '/Fixtures/seeds/testdata_seed.sql');
        $this->getConnection()->query($statement);
    }

    public function initDatabase()
    {
        $statement = file_get_contents(TESTS_ROOT . '/Fixtures/seeds/create_tables.sql');
        $this->getConnection()->query($statement);
    }

    public function tearDownDatabase()
    {
        $statement = file_get_contents(TESTS_ROOT . '/Fixtures/seeds/drop_tables.sql');
        $this->getConnection()->query($statement);
    }

    public function getRowCount($table)
    {
        $statement = sprintf('SELECT COUNT(*) FROM `%s`', $table);
        $res = $this->getConnection()->query($statement);
        return $res->fetchColumn();
    }
}
