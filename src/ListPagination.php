<?php

namespace Ifedko\DoctrineDbalPagination;

/**
 * Class ListPagination
 *
 * @package Ifedko\DoctrineDbalPagination
 */
class ListPagination
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var \Ifedko\DoctrineDbalPagination\ListBuilder
     */
    private $listQueryBuilder;

    /**
     * @var callable|null
     */
    private $pageItemsMapCallback;

    /**
     * @param \Ifedko\DoctrineDbalPagination\ListBuilder $listQueryBuilder
     */
    public function __construct(ListBuilder $listQueryBuilder)
    {
        $this->listQueryBuilder = $listQueryBuilder;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function get($limit, $offset)
    {
        $limit = (intval($limit) > 0) ? intval($limit) : self::DEFAULT_LIMIT;
        $offset = (intval($offset) >= 0) ? $offset : self::DEFAULT_OFFSET;

        $pageItems = $this->listQueryBuilder->query()
            ->setMaxResults($limit)->setFirstResult($offset)->execute()->fetchAll();

        return [
            'total' => $this->listQueryBuilder->totalQuery()
                ->execute()->rowCount(),

            'items' => is_null($this->pageItemsMapCallback) ?
                $pageItems : array_map($this->pageItemsMapCallback, $pageItems),

            'sorting' => $this->listQueryBuilder->sortingParameters()
        ];
    }

    /**
     * @param callback $callback
     */
    public function definePageItemsMapCallback($callback)
    {
        $this->pageItemsMapCallback = $callback;
    }
}
