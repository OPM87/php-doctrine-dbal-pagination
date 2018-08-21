<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Test\Filter\Base;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\Base\DateRangeFilter;

/**
 * Class DateRangeFilterTest
 *
 * @package Ifedko\DoctrineDbalPagination\Test\Filter\Base
 */
class DateRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApplyReturnQueryBuilderSuccess()
    {
        $queryBuilder = new QueryBuilder(DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ]));

        $dateRangeFilter = new DateRangeFilter('field');
        $dateRangeFilter->bindValues(['begin' => '2015-10-01 00:00:00', 'end' => '2015-10-31 23:59:59']);
        $queryBuilder = $dateRangeFilter->apply($queryBuilder);

        $this->assertInstanceOf('Doctrine\DBAL\Query\QueryBuilder', $queryBuilder);
    }

    public function testCorrectParameters()
    {
        $dateRangeFilter = new DateRangeFilter('field');
        $dateRangeFilter->bindValues(['begin' => '2015-10-01 00:00:00', 'end' => '2015-10-31 23:59:59']);
        $this->assertEquals([
            '2015-10-01 00:00:00',
            '2015-10-31 23:59:59'
        ], [
            $dateRangeFilter->getBeginValue(),
            $dateRangeFilter->getEndValue()
        ]);
    }
    
    public function testIncorrectParameters()
    {
        $dateRangeFilter = new DateRangeFilter('field');
        $dateRangeFilter->bindValues(['something' => '2015-10-01 00:00:00', 'else' => '2015-10-31 23:59:59']);
        $this->assertNotEquals([
            '2015-10-01 00:00:00',
            '2015-10-31 23:59:59'
        ], [
            $dateRangeFilter->getBeginValue(),
            $dateRangeFilter->getEndValue()
        ]);
    }
}
