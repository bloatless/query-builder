<?php

namespace Bloatless\QueryBuilder\Test\Unit\QueryBuilder;

require_once __DIR__ . '/../AbstractQueryBuilderTest.php';
require_once __DIR__ . '/../../Fixtures/StatementBuilderMock.php';
require_once __DIR__ . '/../../Fixtures/WhereQueryBuilderMock.php';

use Bloatless\QueryBuilder\Test\Fixtures\StatementBuilderMock;
use Bloatless\QueryBuilder\Test\Fixtures\WhereQueryBuilderMock;
use Bloatless\QueryBuilder\Test\Unit\AbstractQueryBuilderTest;

class WhereQueryBuilderTest extends AbstractQueryBuilderTest
{
    public $defaultCredentials;

    public function setUp(): void
    {
        parent::setUp();
        $configData = include TESTS_ROOT . '/Fixtures/config/config.php';
        $config = $configData['db'];
        $defaultConnection = $config['default_connection'];
        $this->defaultCredentials = $config['connections'][$defaultConnection];
    }

    public function testSetter()
    {

        $statementBuilder = new StatementBuilderMock;
        $builder = new WhereQueryBuilderMock($this->getConnection(), $statementBuilder);
        $builder = $builder->where('customer_id', '=', 1)
            ->whereEquals('customer_id', 1)
            ->orWhere('customer_id', '=', 2)
            ->whereIn('customer_id', [1, 2])
            ->whereNotIn('customer_id', [3, 4])
            ->orWhereIn('customer_id', [1, 2])
            ->orWhereNotIn('customer_id', [3, 4])
            ->whereBetween('customer_id', 1, 2)
            ->orWhereBetween('customer_id', 3, 4)
            ->whereNull('firstname')
            ->whereNotNull('lastname')
            ->orWhereNull('lastname')
            ->orWhereNotNull('firstname')
            ->whereRaw('customer_id = 1')
            ->orWhereRaw('customer_id = 2');
        $this->assertInstanceOf(WhereQueryBuilderMock::class, $builder);
    }
}
