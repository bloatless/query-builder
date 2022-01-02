<?php

namespace Bloatless\QueryBuilder\Test\Fixtures;

use Bloatless\QueryBuilder\QueryBuilder\WhereQueryBuilder;

require_once SRC_ROOT . '/QueryBuilder/WhereQueryBuilder.php';

class WhereQueryBuilderMock extends WhereQueryBuilder
{
    public function reset(): void
    {
        // just a mock
    }

    protected function buildStatement(): string
    {
        return '';
    }
}
