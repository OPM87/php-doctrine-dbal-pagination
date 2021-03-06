<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Test\Sorting;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Sorting\ByColumn;
use Mockery;

/**
 * Class ByColumnTest
 *
 * @package Ifedko\DoctrineDbalPagination\Test\Sorting
 */
class ByColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testConfiguresSortingInQueryBuilder()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->with('t.name', 'DESC')->once();

        self::sortingByNameWithParameters(
            [
                'sortBy' => 'name',
                'sortOrder' => 'desc'
            ]
        )->apply($builder);
    }

    public function testSortingCanBeDefinedWithoutTheDirection()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->with('t.name', null)->once();

        self::sortingByNameWithParameters(
            [
                'sortBy' => 'name',
            ]
        )->apply($builder);
    }

    public function testDoesNoSortingWhenNoParametersWereGiven()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->never();

        self::sortingByNameWithParameters([])->apply($builder);
    }

    public function testWillIgnoreUnknownParameters()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->never();

        self::sortingByNameWithParameters(
            [
                'sortBy' => 'table.evil',
            ]
        )->apply($builder);
    }

    public function testPermanentDefaultSortingCanBeGiven()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->with('t.name', 'DESC')->once();

        self::sortingByNameWithParameters([], 'DESC')->apply($builder);
    }

    public function testPermanentDefaultSortingIsActiveEvenWhenSortingForOtherColumnIsRequested()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->with('t.name', 'DESC')->once();

        self::sortingByNameWithParameters(
            [
                'sortBy' => 'someOtherColumn',
                'sortOrder' => 'ASC'
            ],
            'DESC'
        )->apply($builder);
    }

    public function testPermanentDefaultSortingCanBeOverridden()
    {
        $builder = Mockery::mock(QueryBuilder::class);
        $builder->shouldReceive('addOrderBy')->with('t.name', 'ASC')->once();

        self::sortingByNameWithParameters(
            [
                'sortBy' => 'name',
                'sortOrder' => 'ASC'
            ],
            'DESC'
        )->apply($builder);
    }

    public function testReturnsSortingParametersThatActuallyWereApplied()
    {
        $sorting = new ByColumn('name', 't.name');

        $this->assertSame(
            [
                'sortBy' => 'name',
                'sortOrder' => 'DESC'
            ],
            $sorting->bindValues([
                'foo' => 'bar',
                'sortBy' => 'name',
                'sortOrder' => 'desc'
            ])
        );
    }

    /**
     * @param      $parameters
     * @param null $defaultDirection
     *
     * @return ByColumn
     */
    private static function sortingByNameWithParameters($parameters, $defaultDirection = null)
    {
        $sorting = new ByColumn('name', 't.name', $defaultDirection);
        $sorting->bindValues($parameters);

        return $sorting;
    }
}
