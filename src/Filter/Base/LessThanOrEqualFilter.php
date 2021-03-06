<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Filter\Base;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\FilterInterface;

/**
 * Class LessThanOrEqualFilter
 *
 * @package Ifedko\DoctrineDbalPagination\Filter\Base
 */
class LessThanOrEqualFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $column
     */
    public function __construct($column)
    {
        $this->column = $column;
    }

    /**
     * {@inheritDoc}
     */
    public function bindValues($values): FilterInterface
    {
        $this->value = $values;

        return $this;
    }

    /**
     * @param \Doctrine\DBAL\Query\QueryBuilder $builder
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function apply(QueryBuilder $builder): QueryBuilder
    {
        $builder->andWhere($builder->expr()->lte($this->column, $builder->expr()->literal($this->value)));

        return $builder;
    }
}
