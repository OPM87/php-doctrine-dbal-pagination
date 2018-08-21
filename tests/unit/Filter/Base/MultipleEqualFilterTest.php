<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Test\Filter\Base;

use Ifedko\DoctrineDbalPagination\Filter\Base\MultipleEqualFilter;
use Mockery;

/**
 * Class MultipleEqualFilterTest
 *
 * @package Ifedko\DoctrineDbalPagination\Test\Filter\Base
 */
class MultipleEqualFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApplyReturnQueryBuilderSuccess()
    {
        $queryBuilderMock = Mockery::mock('Doctrine\DBAL\Query\QueryBuilder')
            ->makePartial();

        $multipleEqualFilter = new MultipleEqualFilter('field');
        $multipleEqualFilter->bindValues(['value1', 'value2']);
        /** @noinspection PhpParamsInspection */
        $queryBuilder = $multipleEqualFilter->apply($queryBuilderMock);

        $this->assertInstanceOf('Doctrine\DBAL\Query\QueryBuilder', $queryBuilder);
    }
}
