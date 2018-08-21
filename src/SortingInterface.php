<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Interface SortingInterface
 *
 * @package Ifedko\DoctrineDbalPagination
 */
interface SortingInterface
{
    /**
     * @param array $values
     *
     * @return array values that were actually used to define sorting
     */
    public function bindValues(array $values): array;

    /**
     * @param QueryBuilder $builder
     *
     * @return QueryBuilder
     */
    public function apply(QueryBuilder $builder): QueryBuilder;
}
