<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Filter\Base;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\FilterInterface;

/**
 * Class LikeFilter
 *
 * @package Ifedko\DoctrineDbalPagination\Filter\Base
 */
class LikeFilter implements FilterInterface
{
    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string|array $columns
     * @param array        $options
     */
    public function __construct($columns, $options = [])
    {
        $this->columns = (!is_array($columns)) ? [$columns] : $columns;
        $this->options = array_merge(['operator' => 'LIKE'], $options);
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
     * {@inheritDoc}
     */
    public function apply(QueryBuilder $builder): QueryBuilder
    {
        $orConditions = [];
        foreach ($this->columns as $column) {
            $orCondition = $builder->expr()->comparison(
                $column,
                $this->options['operator'],
                $builder->expr()->literal('%' . $this->value . '%', \PDO::PARAM_STR)
            );
            $orConditions[] = $orCondition;
        }
        $builder->andWhere($builder->expr()->orX()->addMultiple($orConditions));

        return $builder;
    }
}
