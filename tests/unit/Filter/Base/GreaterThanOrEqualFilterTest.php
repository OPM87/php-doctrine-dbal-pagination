<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Test\Filter\Base;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\Base\GreaterThanOrEqualFilter;

/**
 * Class GreaterThanOrEqualFilterTest
 *
 * @package Ifedko\DoctrineDbalPagination\Test\Filter\Base
 */
class GreaterThanOrEqualFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesGreaterThanOrEqualCondition()
    {
        $queryBuilder = new QueryBuilder(DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ]));

        $this->assertContains(
            "table.startDate >= '2015-09-01'",
            (new GreaterThanOrEqualFilter('table.startDate'))
                ->bindValues('2015-09-01')
                ->apply($queryBuilder)->getSQL()
        );
    }
}
