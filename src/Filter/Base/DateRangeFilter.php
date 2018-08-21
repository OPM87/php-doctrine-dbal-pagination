<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Filter\Base;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\Filter\FilterInterface;

/**
 * Class DateRangeFilter
 *
 * @package Ifedko\DoctrineDbalPagination\Filter\Base
 */
class DateRangeFilter implements FilterInterface
{
    /** @var string */
    private $column;

    /** @var string|null */
    private $beginValue;

    /** @var string|null */
    private $endValue;

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
    public function apply(QueryBuilder $builder): QueryBuilder
    {
        if (!$this->beginValue && !$this->endValue) {
            return $builder;
        }

        $andCondition = $builder->expr()->andX();
        if ($this->beginValue) {
            $startExpression = $builder->expr()->gte($this->column, $builder->expr()->literal($this->beginValue));
            $andCondition->add($startExpression);
        }

        if ($this->endValue) {
            $endExpression = $builder->expr()->lte($this->column, $builder->expr()->literal($this->endValue));
            $andCondition->add($endExpression);
        }

        $builder->andWhere($andCondition);

        return $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function bindValues($values): FilterInterface
    {
        $beginValue = !empty($values['begin']) ? $values['begin'] : null;
        $endValue = !empty($values['end']) ? $values['end'] : null;

        $this->beginValue = $beginValue;
        $this->endValue = $endValue;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBeginValue(): ?string
    {
        return $this->beginValue;
    }

    /**
     * @return string|null
     */
    public function getEndValue(): ?string
    {
        return $this->endValue;
    }
}
