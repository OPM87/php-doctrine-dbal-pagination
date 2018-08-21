<?php
declare(strict_types=1);

namespace Ifedko\DoctrineDbalPagination\Sorting;

use Doctrine\DBAL\Query\QueryBuilder;
use Ifedko\DoctrineDbalPagination\SortingInterface;

/**
 * Class ByColumn
 *
 * @package Ifedko\DoctrineDbalPagination\Sorting
 */
class ByColumn implements SortingInterface
{
    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';
    const PARAM_NAME_SORT_BY = 'sortBy';
    const PARAM_NAME_SORT_ORDER = 'sortOrder';

    /** @var string|null */
    private $sortColumn;
    /** @var string|null */
    private $sortDirection;
    /** @var string */
    private $columnAlias;
    /** @var string */
    private $columnName;

    /**
     * @var null|string one of self::SORT_*
     */
    private $defaultDirection;

    /**
     * ByColumn constructor.
     *
     * @param string      $columnAlias
     * @param string      $columnName
     * @param null|string $defaultDirection
     */
    public function __construct(string $columnAlias, string $columnName, ?string $defaultDirection = null)
    {
        $this->columnAlias = $columnAlias;
        $this->columnName = $columnName;
        $this->defaultDirection = $defaultDirection;
    }

    /**
     * @param array $values
     *
     * @return array values that were actually used to define sorting
     */
    public function bindValues(array $values): array
    {
        $appliedValues = [];
        $this->sortColumn = null;

        if (isset($values[self::PARAM_NAME_SORT_BY]) && ($values[self::PARAM_NAME_SORT_BY] === $this->columnAlias)) {

            $this->sortColumn = $this->columnName;
            $this->sortDirection = isset($values[self::PARAM_NAME_SORT_ORDER]) &&
            in_array(strtoupper($values[self::PARAM_NAME_SORT_ORDER]), [self::SORT_ASC, self::SORT_DESC], true) ?
                strtoupper($values['sortOrder']) : $this->defaultDirection;
        } elseif ($this->defaultDirection !== null) {
            $this->sortColumn = $this->columnName;
            $this->sortDirection = $this->defaultDirection;
        }

        if ($this->sortColumn) {
            $appliedValues[self::PARAM_NAME_SORT_BY] = $this->columnAlias;
        }

        if ($this->sortColumn && $this->sortDirection) {
            $appliedValues[self::PARAM_NAME_SORT_ORDER] = $this->sortDirection;
        }

        return $appliedValues;
    }

    /**
     * @param QueryBuilder $builder
     *
     * @return QueryBuilder
     */
    public function apply(QueryBuilder $builder): QueryBuilder
    {
        if ($this->sortColumn) {
            $builder->addOrderBy($this->sortColumn, $this->sortDirection);
        }

        return $builder;
    }
}
