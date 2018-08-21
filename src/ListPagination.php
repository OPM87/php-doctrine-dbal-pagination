<?php
declare(strict_types=1);

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

    /**  @var ListBuilder */
    private $listQueryBuilder;

    /** @var callable|null */
    private $pageItemsMapCallback;

    /**
     * @param ListBuilder $listQueryBuilder
     */
    public function __construct(ListBuilder $listQueryBuilder)
    {
        $this->listQueryBuilder = $listQueryBuilder;
    }

    /**
     * @param int $limit  must be >0 , otherwise DEFAULT_LIMIT will be taken
     * @param int $offset must be >=0 , otherwise DEFAULT_OFFSET will be taken
     *
     * @return array
     */
    public function get(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): array
    {
        $limit = $limit > 0 ? $limit : self::DEFAULT_LIMIT;
        $offset = $offset >= 0 ? $offset : self::DEFAULT_OFFSET;

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
    public function definePageItemsMapCallback(callable $callback)
    {
        $this->pageItemsMapCallback = $callback;
    }
}
