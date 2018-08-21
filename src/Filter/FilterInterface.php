<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Filter;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Interface FilterInterface
 *
 * @package Ifedko\DoctrineDbalPagination\Filter
 */
interface FilterInterface
{
    /**
     * @param mixed $values
     *
     * @return FilterInterface
     */
    public function bindValues($values): FilterInterface;

    /**
     * @param \Doctrine\DBAL\Query\QueryBuilder $builder
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function apply(QueryBuilder $builder): QueryBuilder;
}
