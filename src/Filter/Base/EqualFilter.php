<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Filter\Base;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\FilterInterface;

/**
 * Class EqualFilter
 *
 * @package Ifedko\DoctrineDbalPagination\Filter\Base
 */
class EqualFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $column;

    /**
     * @var int
     */
    private $value;

    /**
     * @var
     */
    private $type;

    /**
     * @param string $column
     * @param string $type \PDO::PARAM_* constant
     */
    public function __construct($column, $type)
    {
        $this->column = $column;
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function bindValues($values): FilterInterface
    {
        $this->value = ($this->type === \PDO::PARAM_INT) ? intval($values) : $values;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(QueryBuilder $builder): QueryBuilder
    {
        $builder->andWhere($builder->expr()->eq($this->column, $builder->expr()->literal($this->value, $this->type)));

        return $builder;
    }
}
